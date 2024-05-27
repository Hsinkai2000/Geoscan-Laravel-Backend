<?php

namespace App\Models;

use DateTime;
use App\Models\Project;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;


class NoiseMeter extends Model
{
    use HasFactory;
    protected $table = 'noise_meters';
    protected $fillable = ['serial_number', 'brand', 'last_calibration_date', 'remarks', 'created_at', 'updated_at'];


    public function measurement_point(): BelongsTo
    {
        return $this->belongsTo(MeasurementPoint::class, 'noise_meter_id');
    }

    public function noise_data(): HasMany
    {
        return $this->hasMany(NoiseData::class, 'noise_meter_id');
    }

    public function project_data(): HasMany
    {
        $project_id = $this->project_id ?? "NULL";
        return $this->hasMany(NoiseDatum::class, 'project_id', 'project_id')->where('project_id', $project_id);
    }

    public function hasProject()
    {
        return $this->measurement_point()->hasProject() !== null;
    }

    public function has_running_project()
    {

        return $this->project->isRunning();
    }

    public function dose_flag_reset()
    {
        $last_leq = $this->project->latest()->first();
        $received_at = new DateTime($last_leq->received_at);

        $hour = $received_at->format('H');
        $min = $received_at->format('i');

        $reset_hours = $this->dose_reset_hours($received_at);

        if (in_array($hour, $reset_hours) && ($min < 6)) {
            return true;
        }
        return false;
    }
    function dose_reset_hours($time)
    {
        if ($this->is_leq12($time)) {
            return [7, 19];
        } else {
            return array_merge(range(0, 6), range(19, 23));
        }
    }

    function is_leq12($time)
    {
        return $time->format('H') < 12;
    }

    public function leq5MinsExceedAndAlert($currentLeq = null)
    {
        $currentLeq = $currentLeq ?: $this->project->latest()->first();
        $limit = $this->soundLimit()->leq5Limit($currentLeq->received_at);

        $shouldAlert = $currentLeq->leq >= $limit && $this->leq5LastAlertAllowed($currentLeq->received_at);
        return [$shouldAlert, $currentLeq->leq, $limit];
    }

    public function leqHourlyExceedAndAlert($currentLeq = null)
    {
        $currentLeq = $currentLeq ?: $this->project->latest()->first();
        $leqHourly = $this->isLeq12($currentLeq->received_at) ? $this->leq12Hours($currentLeq->received_at) : $this->leq1Hour($currentLeq->received_at);
        $lastAlertAt = $this->isLeq12($currentLeq->received_at) ? $this->leq12HoursLastAlertAt : $this->leq1HourLastAlertAt;
        $limit = $this->soundLimit()->leqHourlyLimit($currentLeq->received_at);

        $shouldAlert = $leqHourly >= $limit && $this->leqHourlyLastAlertAllowed($lastAlertAt, $currentLeq->received_at);
        return [$shouldAlert, $leqHourly, $limit];
    }

    public function doseExceedAndAlert($currentLeq = null)
    {
        $currentLeq = $currentLeq ?: $this->project->latest()->first();
        $percentage = $this->dosePercentage($currentLeq->received_at);
        $lastAlertAt = $percentage < 100 ? $this->dose70LastAlertAt : $this->dose100LastAlertAt;

        $shouldAlert = $this->doseExceed($percentage) && $this->doseLastAlertAllowed($lastAlertAt, $currentLeq->received_at);
        return [$shouldAlert, $percentage];
    }

    public function sendDoseAlert($percentage, $currentLeq)
    {
        $category = "noise dosage";
        $subject = "Project Exceed Dosage Limit for SN: " . strtoupper($this->serial_number);

        foreach ($this->contacts as $contact) {
            if (!in_array($contact->id, $this->project_contact_ids)) {
                continue;
            }

            $message = $this->smsMessageBuilder($contact, $category, $currentLeq, "{$percentage}%", ($percentage < 100 ? '70%' : '100%'));

            if ($contact->shouldAlert('email', $currentLeq->received_at)) {
                echo "== EMAIL: " . $contact->email . "<br />";
                // Replace the code below with your email sending logic in PHP
                // Emailer::contact($contact->email, $subject, "{$message}<br /><br />Please log in to Geoscan Real Time Monitoring for more information.")->deliver();
            }

            if ($contact->shouldAlert('sms', $currentLeq->received_at)) {
                echo "== SMS: " . $message . "<br />";
                foreach (explode(';', $contact->phone_number) as $phone) {
                    $this->sendSms($phone, $message);
                }
            }
        }
    }

    public function leq_5_mins_exceed_and_alert($currentLeq = null)
    {
        $currentLeq = $currentLeq ?: $this->project->orderBy('received_at', 'desc')->first();
        $limit = $this->soundLimit()->leq5Limit($currentLeq->received_at);

        $shouldAlert = $currentLeq->leq >= $limit && $this->leq5LastAlertAllowed($currentLeq->received_at);
        return [$shouldAlert, $currentLeq->leq, $limit];
    }

    public function check_last_data_for_alert()
    {
        $last_leq = $this->project->latest()->first();

        list($leq_5mins_alert, $calculated_value, $limit) = $this->leq_5_mins_exceed_and_alert();
        if ($leq_5mins_alert) {
            $this->send_last_data_alert("Leq5min", $last_leq, $calculated_value, $limit);
            $this->update_attributes(['leq_5_mins_last_alert_at' => $last_leq->received_at]);
        }

        list($leq_hour_alert, $calculated_value, $limit) = $this->leq_hourly_exceed_and_alert();
        if ($leq_hour_alert) {
            $category = $this->is_leq12($last_leq->received_at) ? "Leq12hr" : "Leq1hr";
            $params = [$category . '_last_alert_at' => $last_leq->received_at];
            $this->send_last_data_alert($category, $last_leq, $calculated_value, $limit);
            $this->update_attributes($params);
        }

        list($dose_alert, $percentage) = $this->dose_exceed_and_alert();
        if ($dose_alert) {
            if ($percentage >= 70 && $percentage <= 99) {
                $params = ['dose_70_last_alert_at' => $last_leq->received_at, 'dose_flag' => 1];
            } else {
                $params = ['dose_100_last_alert_at' => $last_leq->received_at, 'dose_flag' => 2];
            }
            $this->send_dose_alert($percentage, $last_leq);
            $this->update_attributes($params);
        }
    }

}
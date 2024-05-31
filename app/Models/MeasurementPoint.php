<?php

namespace App\Models;

use App\Mail\EmailAlert;
use App\Services\TwilioService;
use DateTime;
use Exception;
use function PHPUnit\Framework\isNull;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class MeasurementPoint extends Model
{
    use HasFactory;

    const SMS_TEMPLATE = 'sms.sms_leq_limit_exceeded';

    protected $table = 'measurement_points';

    protected $fillable = [
        'project_id',
        'noise_meter_id',
        'concentrator_id',
        'point_name',
        'remarks',
        'inst_leq',
        'leq_temp',
        'dose_flag',
        'device_latling',
        'device_location',
        'noise_alert_at',
        'leq_5_mins_last_alert_at',
        'leq_1_hour_last_alert_at',
        'leq_12_hours_last_alert_at',
        'dose_70_last_alert_at',
        'dose_100_last_alert_at',
        'last_alert',
        'created_at',
        'updated_at',
    ];

    protected $casts = [
        'id' => 'integer',
        'project_id' => 'integer',
        'noise_meter_id' => 'integer',
        'concentrator_id' => 'integer',
        'inst_leq' => 'float',
        'leq_temp' => 'integer',
        'dose_flag' => 'decimal:11',
        'noise_alert_at' => 'datetime',
        'leq_5_mins_last_alert_at' => 'datetime',
        'leq_1_hour_last_alert_at' => 'datetime',
        'leq_12_hours_last_alert_at' => 'datetime',
        'dose_70_last_alert_at' => 'datetime',
        'dose_100_last_alert_at' => 'datetime',
        'last_alert' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    private static $timeSlots = [
        '7am_7pm' => ['start' => '07:00', 'end' => '18:59'],
        '7pm_10pm' => ['start' => '19:00', 'end' => '06:59'],
        '10pm_12am' => ['start' => '19:00', 'end' => '06:59'],
        '12am_7am' => ['start' => '19:00', 'end' => '06:59'],
    ];

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class, 'project_id', 'id');
    }

    public function concentrator(): HasOne
    {
        return $this->hasOne(Concentrator::class, 'id', 'concentrator_id');
    }
    public function noiseMeter(): HasOne
    {
        return $this->hasOne(NoiseMeter::class, 'id', 'noise_meter_id');
    }

    public function noiseData(): HasMany
    {
        return $this->hasMany(NoiseData::class, 'measurement_point_id', 'id');
    }
    public function soundLimit(): HasOne
    {
        return $this->hasOne(SoundLimit::class, 'measurement_point_id', 'id');
    }

    public function hasProject()
    {
        return $this->project !== null;
    }

    public function has_running_project()
    {
        return $this->project->isRunning();
    }
    public function dose_flag_reset()
    {
        $lastLeq = $this->getLastLeqData();

        if ($lastLeq) {
            $last_date_time = $lastLeq->received_at;
            $resetHours = $this->doseResetHours($last_date_time);
            $hour = $last_date_time->format('H');
            $minute = $last_date_time->format('i');

            return in_array($hour, $resetHours) && ($minute < 6);
        }

        return false;
    }

    public function doseResetHours($time)
    {
        return $this->isLeq12($time) ? [7, 19] : array_merge(range(0, 6), range(19, 23));
    }

    public function isLeq12($time)
    {
        return $time->format('H') < 12;
    }

    public function check_last_data_for_alert()
    {
        $leq_12_should_alert = false;
        $leq_1_should_alert = false;
        $last_noise_data = $this->getLastLeqData();

        [$leq_5mins_should_alert, $limit] = $this->leq_5_mins_exceed_and_alert($last_noise_data);

        [$decision, $last_data_datetime] = $this->soundLimit->check_12_1_hour_limit_type($last_noise_data->received_at);

        if ($decision == '12') {
            [$leq_12_should_alert, $limit] = $this->leq_12_hours_exceed_and_alert($last_noise_data, $last_data_datetime);
        } else {
            [$leq_1_should_alert, $limit] = $this->leq_1_hour_exceed_and_alert($last_noise_data, $last_data_datetime);
        }

        $data = [
            "client_name" => $this->project->contact->contact_person_name,
            "jobsite_location" => $this->project->jobsite_location,
            "serial_number" => $this->noiseMeter->serial_number,
            "leq_value" => $last_noise_data->leq,
            "exceeded_limit" => $limit,
            "leq_type" => null,
            "exceeded_time" => $last_noise_data->received_at,
        ];

        if ($leq_5mins_should_alert) {
            // $this->leq_5_mins_last_alert_at = $last_noise_data->received_at;
            // $this->save();
            $data["leq_type"] = "5min";
            // $this->send_alert($data);
        }
        if ($leq_12_should_alert) {
            // $this->leq_12_hours_last_alert_at = $last_noise_data->received_at;
            // $this->save();
            $data["leq_type"] = "12hours";
            // $this->send_alert($data);
        } else if ($leq_1_should_alert) {
            // $this->leq_1_hour_last_alert_at = $last_noise_data->received_at;
            // $this->save();
            $data["leq_type"] = "1hour";
            // $this->send_alert($data);
        }
    }
    private function send_alert($data)
    {
        [$phone_number, $email] = $this->project->get_contact_details();

        [$email_messageid, $email_messagedebug] = $this->send_email($data, $email);
        [$sms_messageid, $sms_status] = $this->send_sms($data, $phone_number);

        DB::table('alert_logs')->insert([
            'event_timestamp' => $data["exceeded_time"],
            'email_messageId' => $email_messageid,
            'email_debug' => $email_messagedebug,
            'sms_messageId' => $sms_messageid,
            'sms_status' => $sms_status,
            'created_at' => now(),
        ]);

    }

    private function send_email($data, $email)
    {
        $email = $this->project->contact->email;

        if (!empty($email)) {
            try {
                $email_response = Mail::to($email)->send(new EmailAlert($data));
                $email_messageid = $email_response->getSymfonySentMessage()->getMessageId();
                $email_messagedebug = $email_response->getSymfonySentMessage()->getDebug();
            } catch (Exception $e) {
                debug_log('error sending email', [$e->getMessage()]);
                $email_messagedebug($e->getMessage());
            }
        }
        return [$email_messageid, $email_messagedebug];
    }

    private function send_sms($data, $phone_number)
    {
        $sms_messageid = '';
        $sms_status = 'SMS not sent';
        if (!empty($phone_number)) {
            $phone_number = "+65" . $phone_number;
            try {
                $twilio_service = new TwilioService();
                $sms_response = $twilio_service->sendMessage($phone_number, self::SMS_TEMPLATE, $data);
                if (isNull($sms_response->sid)) {
                    $sms_messageid = $sms_response->sid;
                    $sms_status = "SMS sending";
                }

            } catch (Exception $e) {
                debug_log("Message not sent", [$e->getMessage()]);
            }
        } else {
            debug_log("No phone number found");
        }
        return [$sms_messageid, $sms_status];
    }

    private function leq_5_mins_exceed_and_alert($last_noise_data = null)
    {
        $limit = $this->soundLimit->leq5_limit($last_noise_data->received_at);
        $should_alert = $last_noise_data->leq >= $limit && $this->leq_last_alert_allowed($this->leq_5_mins_last_alert_at, $last_noise_data->received_at);
        debug_log("leq5 : leq5 should alert : leq5 limit", [$last_noise_data->leq, $should_alert, $limit]);
        return [$should_alert, $limit];
    }

    private function leq_12_hours_exceed_and_alert($last_noise_data = null, $last_data_datetime)
    {
        $twelve_hr_leq = $this->calc_12_hour_leq();
        $limit = $this->soundLimit->leq12h_limit($last_data_datetime);
        $should_alert = $twelve_hr_leq >= $limit && $this->leq_last_alert_allowed($this->leq_12_hours_last_alert_at, $last_noise_data->received_at);
        debug_log("12hrleq : leq12hr should alert:leq12hr limit ", [$twelve_hr_leq, $should_alert, $limit]);
        return [$should_alert, $limit];
    }

    private function leq_1_hour_exceed_and_alert($last_noise_data = null, $last_data_datetime)
    {
        $one_hr_leq = $this->calc_1_hour_leq();
        $limit = $this->soundLimit->leq1h_limit($last_data_datetime);
        $should_alert = $one_hr_leq >= $limit && $this->leq_last_alert_allowed($this->leq_1_hour_last_alert_at, $last_noise_data->received_at);
        debug_log("one_hr_leq : leq1hr should alert:leq1hr limit ", [$one_hr_leq, $should_alert, $limit]);
        return [$should_alert, $limit];
    }

    private function get_current_date($param = null)
    {
        $last_noise_data_datetime = $this->getLastLeqData()->received_at;
        return $param == null ? $last_noise_data_datetime->format('Y-m-d') : $last_noise_data_datetime->modify($param)->format('Y-m-d');
    }

    private function get_final_time_start_stop($last_noise_data_date, $time_range)
    {
        $last_noise_data_start_date = $last_noise_data_date;
        $last_noise_data_end_date = $last_noise_data_date;

        if ($time_range == array_keys(self::$timeSlots)[3]) {
            $last_noise_data_start_date = $this->get_current_date('-1 day');
        } else if ($time_range != array_keys(self::$timeSlots)[0]) {
            $last_noise_data_end_date = $this->get_current_date('+1 day');
        }

        $start_time = new DateTime($last_noise_data_start_date . ' ' . self::$timeSlots[$time_range]['start']);
        $end_time = new DateTime($last_noise_data_end_date . ' ' . self::$timeSlots[$time_range]['end']);

        return [$start_time, $end_time];
    }

    private function get_hour_to_now_leq()
    {
        $last_noise_data_base_hour = $this->getLastLeqData()->received_at;
        $last_noise_data_base_hour->setTime($last_noise_data_base_hour->format("H"), 0, 0);
        $hour_to_now_leqs = $this->noiseData()->where('received_at', '>=', $last_noise_data_base_hour)->get()->reverse();
        return $hour_to_now_leqs;
    }

    private function get_timesslot_start_end_datetime()
    {
        $noise_data_curr_time_string = $this->getLastLeqData()->received_at;
        [$day, $time_range] = $this->soundLimit->getTimeRange($noise_data_curr_time_string);

        $last_noise_data_date = $this->get_current_date();
        [$start_datetime, $end_datetime] = $this->get_final_time_start_stop($last_noise_data_date, $time_range);
        return [$start_datetime, $end_datetime];
    }

    private function get_timeslot_to_now_leq()
    {
        [$start_datetime, $end_datetime] = $this->get_timesslot_start_end_datetime();
        $timeslot_to_now_leqs = $this->noiseData()->whereBetween('received_at', [$start_datetime, $end_datetime])->get()->reverse();
        return $timeslot_to_now_leqs;
    }

    private function linearise_leq($leq)
    {
        return pow(10, $leq / 10);
    }

    private function convert_to_db($avg_leq)
    {
        return round(10 * log10($avg_leq), 1);
    }

    private function calc_leq($data)
    {
        $sum = 0.0;
        foreach ($data as $leqData) {
            $currentLeq = $leqData->leq;
            $sum += round($this->linearise_leq($currentLeq), 1);
        }

        $avgLeq = $sum / count($data);
        $calculatedLeq = $this->convert_to_db($avgLeq);

        return $calculatedLeq;
    }

    private function calc_12_hour_leq()
    {
        $timeslot_to_now_leqs = $this->get_timeslot_to_now_leq();
        return $this->calc_leq($timeslot_to_now_leqs);
    }

    private function calc_1_hour_leq()
    {
        $hour_to_now_leqs = $this->get_hour_to_now_leq();
        return $this->calc_leq($hour_to_now_leqs);
    }

    private function leq_last_alert_allowed($leq_freq_last_alert_at, $last_received_datetime)
    {

        if (!is_null($leq_freq_last_alert_at) && ($last_received_datetime->getTimestamp() - $leq_freq_last_alert_at->getTimestamp()) <= 3 * 3600) {
            return false;
        }
        return true;
    }

    public function getLastLeqData()
    {
        return $this->noiseData()->latest()->first();
    }

}
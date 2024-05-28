<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use DateTime;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SoundLimit extends Model
{
    use HasFactory;
    protected $table = 'sound_limits';

    protected $fillable = [
        'category',
        'measurement_point_id',
        'mon_sat_7am_7pm_leq5min',
        'mon_sat_7pm_10pm_leq5min',
        'mon_sat_10pm_7am_leq5min',
        'sun_ph_7am_7pm_leq5min',
        'sun_ph_7pm_10pm_leq5min',
        'sun_ph_10pm_7am_leq5min',
        'mon_sat_7am_7pm_leq12hr',
        'mon_sat_7pm_10pm_leq12hr',
        'mon_sat_10pm_7am_leq12hr',
        'sun_ph_7am_7pm_leq12hr',
        'sun_ph_7pm_10pm_leq12hr',
        'sun_ph_10pm_7am_leq12hr',
        // 'mon_sat_10pm_12am_leq5min',
        // 'mon_sat_12am_7am_leq5min',
        // 'sun_ph_10pm_12am_leq5min',
        // 'sun_ph_12am_7am_leq5min',
        // 'mon_sat_10pm_12am_leq12hr',
        // 'mon_sat_12am_7am_leq12hr',
        // 'sun_ph_10pm_12am_leq12hr',
        // 'sun_ph_12am_7am_leq12hr',
        'created_at',
        'updated_at',
    ];

    public function measurementPoint(): BelongsTo
    {
        return $this->belongsTo(MeasurementPoint::class, 'measurement_point_id', 'id');
    }

    private function sound_limits_values_5min()
    {
        return [
            "mon_sat" => [$this->mon_sat_7am_7pm_leq5min, $this->mon_sat_7pm_10pm_leq5min, $this->mon_sat_10pm_7am_leq5min],
            "sun_ph" => [$this->sun_ph_7am_7pm_leq5min, $this->sun_ph_7pm_10pm_leq5min, $this->sun_ph_10pm_7am_leq5min]
        ];
    }

    private function sound_limits_values_12hr()
    {
        return [
            "mon_sat" => [$this->mon_sat_7am_7pm_leq12hr, $this->mon_sat_7pm_10pm_leq12hr, $this->mon_sat_10pm_7am_leq12hr],
            "sun_ph" => [$this->sun_ph_7am_7pm_leq12hr, $this->sun_ph_7pm_10pm_leq12hr, $this->sun_ph_10pm_7am_leq12hr]
        ];

    }

    private static $time_mapper = [
        '7am_7pm' => 0,
        '7pm_10pm' => 1,
        '10pm_7am' => 2,
    ];

    private function time_to_keys($last_data_datetime)
    {
        $day = $last_data_datetime->format('w') == 0 ? 'sun_ph' : 'mon_sat';

        $time_range = $this->getTimeRangeText($last_data_datetime);

        return [$day, $time_range];
    }

    public function leq5_limit($last_data_datetime_string)
    {
        $last_data_datetime = new DateTime($last_data_datetime_string);
        [$day, $time_range] = $this->time_to_keys($last_data_datetime);
        $time_map = self::$time_mapper[$time_range];
        $limit = $this->sound_limits_values_5min()[$day][$time_map];
        debug_log("leq5 limit:", [$limit]);
        return $limit;
    }

    public function leq12_limit($last_data_datetime_string)
    {
        $last_data_datetime = new DateTime($last_data_datetime_string);
    }

    private function getTimeRangeText(DateTime $time)
    {
        $hour = (int) $time->format('G');

        if ($hour >= 7 && $hour <= 18) {
            return '7am_7pm';
        } elseif ($hour >= 19 && $hour <= 21) {
            return '7pm_10pm';
        } elseif ($hour >= 22 || $hour < 7) {
            return '10pm_7am';
        }
    }
}
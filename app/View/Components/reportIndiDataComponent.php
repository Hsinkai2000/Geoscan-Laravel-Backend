<?php

namespace App\View\Components;

use App\Models\MeasurementPoint;
use App\Models\NoiseData;
use Closure;
use DateTime;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class ReportIndiDataComponent extends Component
{
    public MeasurementPoint $measurementPoint;
    // public DateTime $received_at;

    public DateTime $slotDate;

    public string $type;

    /**
     * Create a new component instance.
     */
    public function __construct(MeasurementPoint $measurementPoint, DateTime $slotDate, string $type = '')
    {
        $this->measurementPoint = $measurementPoint;
        $this->slotDate = $slotDate;
        $this->type = $type;

    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View | Closure | string
    {
        $noiseData = $this->measurementPoint->noiseData()->where('received_at', $this->slotDate)->get();

        if ($this->type == '1hLeq') {
            [$one_hr_leq, $num_blanks] = $this->measurementPoint->calc_1_hour_leq($this->slotDate);
            $leq_data = [
                'leq_data' => number_format(round($one_hr_leq, 1), 1),
                'should_alert' => false,
            ];
        } else if ($this->type == '12hLeq') {

            [$twelve_hr_leq, $num_blanks] = $this->measurementPoint->calc_12_hour_leq($this->slotDate);

            $leq_data = [
                'leq_data' => number_format(round($twelve_hr_leq, 1), 1),
                'should_alert' => false,
            ];

        } else if ($this->type == 'dose') {

            if (!empty($noiseData)) {
                $noiseData = new NoiseData(['received_at' => $this->slotDate]);
            } else {
                $noiseData = $noiseData[0];
            }
            $calculated_dose_percentage = $this->measurementPoint->check_last_data_for_alert($noiseData);

            $leq_data = [
                'leq_data' => number_format($calculated_dose_percentage, 2),
                'should_alert' => false,
            ];
        } else {
            if ($noiseData->isNotEmpty()) {

                [$should_alert, $limit] = $this->measurementPoint->leq_5_mins_exceed_and_alert($noiseData[0]);
                $leq_data = [
                    'leq_data' => number_format($noiseData[0]->leq, 1),
                    'should_alert' => $should_alert,
                ];
            } else {
                if ($this->type == '12hLeq') {
                    debug_log('here', [$this->measurementPoint, $this->slotDate]);
                }
                $leq_data = [
                    'leq_data' => '-',
                    'should_alert' => false,
                ];
            }
        }

        return view('components.report-indi-data-component', $leq_data);
    }
}
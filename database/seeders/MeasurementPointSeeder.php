<?php

namespace Database\Seeders;

use App\Models\MeasurementPoint;
use Illuminate\Database\Seeder;

class MeasurementPointSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = \Faker\Factory::create();
        $now = date("Y-m-d H:i:s");
        MeasurementPoint::create([
            "project_id" => 1,
            'noise_meter_id' => 1,
            'concentrator_id' => 1,
            'point_name' => 'zz81889218',
            'remarks' => $faker->text(),
            'device_location' => 'zzKakiBukit',
        ]);
    }
}
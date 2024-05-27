<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\NoiseMeter;

class NoiseMeterSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = \Faker\Factory::create();
        $now = date("Y-m-d");
        NoiseMeter::create([
            'serial_number' => 9900,
            'brand' => 'SINUS TANGO',
            'remarks' => $faker->text(),
            'last_calibration_date' => $now
        ]);
    }
}
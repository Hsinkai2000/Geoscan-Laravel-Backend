<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\NoiseDevice;

class NoiseDeviceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = \Faker\Factory::create();
        $now = date("Y-m-d H:i:s");
        NoiseDevice::create([
            "project_id" => 1,
            'device_id' => 'zz81889218',
            'brand' => 'SINUS TANGO',
            'remarks' => $faker->text(),
            'device_location' => 'zzKakiBukit',
            'last_calibrated_at' => $now,
            'created_at' => $now,
            'updated_at' => $now
        ]);
    }
}
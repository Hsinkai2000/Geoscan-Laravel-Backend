<?php

namespace Database\Seeders;

use App\Models\SoundLimit;
use Illuminate\Database\Seeder;

class SoundLimitSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        $faker = \Faker\Factory::create();
        SoundLimit::create(['measurement_point_id' => 1]);
    }
}
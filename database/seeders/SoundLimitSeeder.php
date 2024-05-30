<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\SoundLimit;

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
<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Concentrator;

class ConcentratorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = \Faker\Factory::create();
        $now = date("Y-m-d H:i:s");
        Concentrator::create([
            "project_id" => 1,
            'device_id' => 'AA818892181234',
            'remarks' => $faker->text(20),
            'concentrator_csq' => 20,
            'concentrator_hp' => 81889218,
            'created_at' => $now,
            'updated_at' => $now
        ]);
    }
}
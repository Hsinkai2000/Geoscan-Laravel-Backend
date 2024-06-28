<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ConcentratorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = \Faker\Factory::create();
        $now = date("Y-m-d H:i:s");
        DB::table('concentrators')->insert([
            [
                "project_id" => 1,
                'device_id' => 'AA818892181234',
                'remarks' => $faker->text(20),
                'concentrator_csq' => 20,
                'battery_voltage' => 15,
                'concentrator_label' => 'concentrator 1',
                'concentrator_hp' => 81889218,
                'last_communication_packet_sent' => $now,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                "project_id" => 7,
                'device_id' => 'BB894722221234',
                'remarks' => $faker->text(20),
                'concentrator_csq' => 25,
                'battery_voltage' => 14,
                'concentrator_label' => 'concentrator 2',
                'concentrator_hp' => 89472222,
                'last_communication_packet_sent' => $now,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                "project_id" => 6,
                'device_id' => 'CC981236731234',
                'remarks' => $faker->text(20),
                'concentrator_csq' => 30,
                'battery_voltage' => 15,
                'concentrator_label' => 'concentrator 3',
                'concentrator_hp' => 98123673,
                'last_communication_packet_sent' => $now,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                "project_id" => 6,
                'device_id' => 'DD123456781234',
                'remarks' => $faker->text(20),
                'concentrator_csq' => 35,
                'battery_voltage' => 17,
                'concentrator_label' => 'concentrator 4',
                'concentrator_hp' => 12345678,
                'last_communication_packet_sent' => $now,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                "project_id" => 6,
                'device_id' => 'EE876543211234',
                'remarks' => $faker->text(20),
                'concentrator_csq' => 40,
                'battery_voltage' => 18,
                'concentrator_label' => 'concentrator 5',
                'concentrator_hp' => 87654321,
                'last_communication_packet_sent' => $now,
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ]);
    }
}
<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Project;

class ProjectSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = \Faker\Factory::create();
        $now = date("Y-m-d H:i:s");
        Project::create([
            'job_number' => 'zzHsinKaiTest',
            'client_name' => 'zzHsinKai',
            'billing_address' => 'zz Kaki Bukit',
            'project_description' => $faker->text(),
            'jobsite_location' => 'zz Kaki Bukit',
            'planning_area' => 'zz12345678',
            'status' => 'Ongoing',
            'sound_quantity_active' => 1,
            'created_at' => $now,
            'updated_at' => $now,
            'project_type' => 'Hospital/Schools',
        ]);
    }
}
<?php

namespace Database\Seeders;

use App\Models\Project;
use Illuminate\Database\Seeder;

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
            'user_id' => 2,
            'job_number' => 'zzHsinKt',
            'client_name' => 'zzHsKai',
            'project_type' => 'sales',
            'end_user_name' => $faker->name(),
            'project_description' => $faker->text(),
            'jobsite_location' => 'zz Kaki Bukit',
            'bca_reference_number' => 'zz12345678',
            'status' => 'Ongoing',
            'created_at' => $now,
            'updated_at' => $now,
        ]);
        Project::create([
            'user_id' => 2,
            'job_number' => 'zzHsiTest',
            'client_name' => 'zKai',
            'project_type' => 'sales',
            'end_user_name' => $faker->name(),
            'project_description' => $faker->text(),
            'jobsite_location' => 'zz Kaki Bukit',
            'bca_reference_number' => 'zz12345678',
            'status' => 'Ongoing',
            'created_at' => $now,
            'updated_at' => $now,
        ]);
        Project::create([
            'user_id' => 1,
            'job_number' => 'zzHest',
            'client_name' => 'zzHsiai',
            'project_type' => 'sales',
            'end_user_name' => $faker->name(),
            'project_description' => $faker->text(),
            'jobsite_location' => 'zz Kaki Bukit',
            'bca_reference_number' => 'zz12345678',
            'status' => 'Ongoing',
            'created_at' => $now,
            'updated_at' => $now,
        ]);
        Project::create([
            'user_id' => 2,
            'job_number' => 'znKaiTest',
            'client_name' => 'zzHsai',
            'project_type' => 'rental',
            'project_description' => $faker->text(),
            'jobsite_location' => 'zz Kaki Bukit',
            'bca_reference_number' => 'zz12345678',
            'status' => 'Ongoing',
            'created_at' => $now,
            'updated_at' => $now,
        ]);
    }
}

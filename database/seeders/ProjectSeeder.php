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
            'job_number' => 'zzHsinKaiTest',
            'client_name' => 'zzHsinKai',
            'project_description' => $faker->text(),
            'jobsite_location' => 'zz Kaki Bukit',
            'BCA Reference Number' => 'zz12345678',
            'status' => 'Ongoing',
            'created_at' => $now,
            'updated_at' => $now,
        ]);
    }
}

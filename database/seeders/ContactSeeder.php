<?php

namespace Database\Seeders;

use App\Models\Contact;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ContactSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //\

        $faker = \Faker\Factory::create();
        Contact::create([
            'project_id' => 1,
            'contact_person_name' => $faker->name,
            'designation' => 'manager',
            'phone_number' => 81889218,
            'email' => 'hsinkai2000@gmail.com'
        ]);
    }
}
<?php

namespace Database\Seeders;

use App\Models\User;
use Faker\Guesser\Name;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = \Faker\Factory::create();
        $now = date("Y-m-d H:i:s");
        User::create([
            'user_type' => 'admin',
            'username' => $faker->name(),
            'email' => $faker->email(),
            'encrypted_password' => bcrypt('abc123456'),
            'created_at' => $now,
            'updated_at' => $now,
        ]);
    }
}

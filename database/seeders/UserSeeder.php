<?php

namespace Database\Seeders;

use App\Models\User;
use Faker\Guesser\Name;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

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
            'password' => Hash::make('abc123456'),
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        User::create([
            'user_type' => 'admin',
            'username' => 'kai1',
            'email' => 'kai123@gmail.com',
            'password' => Hash::make('kai123!'),
            'created_at' => $now,
            'updated_at' => $now,
        ]);
    }
}

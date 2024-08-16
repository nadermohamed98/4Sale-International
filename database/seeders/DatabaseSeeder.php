<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        User::create([
            'name' => 'Admin',
            'email' => 'admin@gmail.com',
            'email_verified_at' => now(),
            'password' => Hash::make("123456"), // password
            'remember_token' => null,
        ]);

        $this->call([
            CustomerSeeder::class,
            MealSeeder::class,
            TableSeeder::class,
            ReservationSeeder::class,
        ]);
    }
}

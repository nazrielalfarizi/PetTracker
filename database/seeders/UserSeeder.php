<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // User 1
        $resident1 = User::create([
            'name'     => 'Nazriel Resident',
            'email'    => 'nazriel@gmail.com',
            'password' => Hash::make('password'),
        ]);

        $resident1->assignRole('resident');

        $resident1->resident()->create([
            'phone_number' => '081234567890',
        ]);

        // User 2
        $resident2 = User::create([
            'name'     => 'User Resident',
            'email'    => 'user@gmail.com',
            'password' => Hash::make('password'),
        ]);

        $resident2->assignRole('resident');

        $resident2->resident()->create([
            'phone_number' => '08871852277',
        ]);
    }
}

<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Membuat User Contoh sebagai Resident
        $resident = User::create([
            'name'     => 'Nazriel Resident',
            'email'    => 'nazriel@gmail.com',
            'password' => Hash::make('password'), // Pastikan menggunakan Hash
        ],

        [
            'name'     => 'User Resident',
            'email'    => 'user@gmail.com',
            'password' => Hash::make('password'), // Pastikan menggunakan Hash
        ]
    );

        // Memberikan role 'resident' (Pastikan RolePermissionSeeder sudah dijalankan sebelumnya)
        $resident->assignRole('resident');

        // Jika kamu ingin membuat banyak user secara otomatis (opsional)
        // User::factory(5)->create()->each(function ($user) {
        //     $user->assignRole('resident');
        // });
        // 2. Berikan Role 'resident'

        $resident->resident()->create([
            'phone_number' => '081234567890',
        ],
        [
            'phone_number' => '08871852277',
        ]);
    }
}

<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Cinema Admin (Super Admin)
        User::create([
            'name' => 'Admin Cinevora',
            'email' => 'admin@cinevora.com',
            'password' => 'password',
            'role' => 'cinema_admin',
            'phone' => '081234567890',
            'email_verified_at' => now(),
        ]);

        // Cafe Admin
        User::create([
            'name' => 'Cafe Staff',
            'email' => 'cafe@cinevora.com',
            'password' => 'password',
            'role' => 'cafe_admin',
            'phone' => '081234567891',
            'email_verified_at' => now(),
        ]);

        // Sample Users
        $users = [
            ['name' => 'Budi Santoso', 'email' => 'budi@example.com', 'phone' => '081200000001'],
            ['name' => 'Siti Rahayu', 'email' => 'siti@example.com', 'phone' => '081200000002'],
            ['name' => 'Andi Pratama', 'email' => 'andi@example.com', 'phone' => '081200000003'],
            ['name' => 'Dewi Lestari', 'email' => 'dewi@example.com', 'phone' => '081200000004'],
            ['name' => 'Rizky Fadillah', 'email' => 'rizky@example.com', 'phone' => '081200000005'],
        ];

        foreach ($users as $userData) {
            User::create([
                'name' => $userData['name'],
                'email' => $userData['email'],
                'password' => 'password',
                'role' => 'user',
                'phone' => $userData['phone'],
                'email_verified_at' => now(),
            ]);
        }
    }
}

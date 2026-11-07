<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $defaultPassword = Hash::make('password');

        // Admin
        User::firstOrCreate(
            ['email' => 'admin@bloodlagbe.com'],
            [
                'name'              => 'Admin User',
                'password'          => $defaultPassword,
                'role'              => 'admin',
                'is_active'         => true,
                'email_verified_at' => now(),
                'phone'             => '01700000000',
                'district'          => 'Dhaka',
            ]
        );

        // Demo donor accounts
        $donors = [
            ['name' => 'Rahim Uddin', 'email' => 'rahim@example.com', 'district' => 'Dhaka', 'phone' => '01711111111'],
            ['name' => 'Fatema Begum', 'email' => 'fatema@example.com', 'district' => 'Chattogram', 'phone' => '01722222222'],
            ['name' => 'Karim Hassan', 'email' => 'karim@example.com', 'district' => 'Sylhet', 'phone' => '01733333333'],
            ['name' => 'Nasrin Akter', 'email' => 'nasrin@example.com', 'district' => 'Rajshahi', 'phone' => '01744444444'],
            ['name' => 'Mizanur Rahman', 'email' => 'mizan@example.com', 'district' => 'Khulna', 'phone' => '01755555555'],
            ['name' => 'Roksana Khatun', 'email' => 'roksana@example.com', 'district' => 'Barishal', 'phone' => '01766666666'],
            ['name' => 'Jahangir Alam', 'email' => 'jahangir@example.com', 'district' => 'Mymensingh', 'phone' => '01777777777'],
            ['name' => 'Shirin Akter', 'email' => 'shirin@example.com', 'district' => 'Comilla', 'phone' => '01788888888'],
        ];

        foreach ($donors as $donor) {
            User::firstOrCreate(
                ['email' => $donor['email']],
                [
                    'name'              => $donor['name'],
                    'password'          => $defaultPassword,
                    'role'              => 'donor',
                    'is_active'         => true,
                    'email_verified_at' => now(),
                    'phone'             => $donor['phone'],
                    'district'          => $donor['district'],
                ]
            );
        }

        // Demo regular user
        User::firstOrCreate(
            ['email' => 'user@bloodlagbe.com'],
            [
                'name'              => 'Demo User',
                'password'          => $defaultPassword,
                'role'              => 'user',
                'is_active'         => true,
                'email_verified_at' => now(),
                'phone'             => '01799999999',
                'district'          => 'Dhaka',
            ]
        );
    }
}

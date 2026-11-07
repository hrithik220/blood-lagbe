<?php

namespace Database\Seeders;

use App\Models\DonorProfile;
use App\Models\User;
use Illuminate\Database\Seeder;

class DonorProfileSeeder extends Seeder
{
    public function run(): void
    {
        $bloodGroups = ['A+', 'A-', 'B+', 'B-', 'AB+', 'AB-', 'O+', 'O-'];

        $donorData = [
            ['email' => 'rahim@example.com',   'blood_group' => 'O+',  'district' => 'Dhaka',      'area' => 'Mirpur',      'last_days' => 120],
            ['email' => 'fatema@example.com',  'blood_group' => 'A+',  'district' => 'Chattogram', 'area' => 'Agrabad',     'last_days' => 60],
            ['email' => 'karim@example.com',   'blood_group' => 'B+',  'district' => 'Sylhet',     'area' => 'Zindabazar',  'last_days' => 200],
            ['email' => 'nasrin@example.com',  'blood_group' => 'AB+', 'district' => 'Rajshahi',   'area' => 'Shaheb Bazar','last_days' => 90],
            ['email' => 'mizan@example.com',   'blood_group' => 'O-',  'district' => 'Khulna',     'area' => 'Khan Jahan',  'last_days' => 150],
            ['email' => 'roksana@example.com', 'blood_group' => 'A-',  'district' => 'Barishal',   'area' => 'Sadar',       'last_days' => 180],
            ['email' => 'jahangir@example.com','blood_group' => 'B-',  'district' => 'Mymensingh', 'area' => 'Sadar',       'last_days' => 300],
            ['email' => 'shirin@example.com',  'blood_group' => 'AB-', 'district' => 'Cumilla',    'area' => 'Kandirpar',   'last_days' => 100],
        ];

        foreach ($donorData as $data) {
            $user = User::where('email', $data['email'])->first();
            if (!$user) continue;

            DonorProfile::firstOrCreate(
                ['user_id' => $user->id],
                [
                    'blood_group'       => $data['blood_group'],
                    'district'          => $data['district'],
                    'area'              => $data['area'],
                    'phone'             => $user->phone,
                    'last_donation_date'=> now()->subDays($data['last_days']),
                    'is_available'      => $data['last_days'] > 90,
                    'is_public'         => true,
                    'bio'               => 'Proud blood donor helping save lives in ' . $data['district'] . '.',
                ]
            );
        }
    }
}

<?php

namespace Database\Seeders;

use App\Models\BloodRequest;
use App\Models\User;
use Illuminate\Database\Seeder;

class BloodRequestSeeder extends Seeder
{
    public function run(): void
    {
        $user = User::where('email', 'user@bloodlagbe.com')->first();
        if (!$user) return;

        $requests = [
            [
                'patient_name'    => 'Patient A.',
                'blood_group'     => 'O+',
                'hospital_name'   => 'Dhaka Medical College Hospital',
                'hospital_address'=> 'Secretariat Road, Dhaka',
                'district'        => 'Dhaka',
                'area'            => 'Shahbag',
                'required_date'   => now()->addDays(2)->format('Y-m-d'),
                'bags_needed'     => 2,
                'contact_number'  => '01700000001',
                'emergency_level' => 'critical',
                'status'          => 'approved',
                'approved_at'     => now()->subHour(),
                'notes'           => 'Surgery scheduled. Urgent need.',
            ],
            [
                'patient_name'    => 'Patient B.',
                'blood_group'     => 'A+',
                'hospital_name'   => 'Chittagong General Hospital',
                'hospital_address'=> 'Sadar Hospital Road, Chattogram',
                'district'        => 'Chattogram',
                'area'            => 'Sadar',
                'required_date'   => now()->addDays(3)->format('Y-m-d'),
                'bags_needed'     => 1,
                'contact_number'  => '01700000002',
                'emergency_level' => 'urgent',
                'status'          => 'approved',
                'approved_at'     => now()->subHours(2),
                'notes'           => 'Post-surgery blood replacement needed.',
            ],
            [
                'patient_name'    => 'Patient C.',
                'blood_group'     => 'B+',
                'hospital_name'   => 'Sylhet MAG Osmani Medical College',
                'hospital_address'=> 'Sadar Thana, Sylhet',
                'district'        => 'Sylhet',
                'area'            => 'Sadar',
                'required_date'   => now()->addDays(5)->format('Y-m-d'),
                'bags_needed'     => 3,
                'contact_number'  => '01700000003',
                'emergency_level' => 'normal',
                'status'          => 'pending',
                'notes'           => 'Thalassemia patient, regular transfusion needed.',
            ],
            [
                'patient_name'    => 'Patient D.',
                'blood_group'     => 'AB-',
                'hospital_name'   => 'Rajshahi Medical College Hospital',
                'hospital_address'=> 'Laxmipur, Rajshahi',
                'district'        => 'Rajshahi',
                'area'            => 'Sadar',
                'required_date'   => now()->addDays(1)->format('Y-m-d'),
                'bags_needed'     => 2,
                'contact_number'  => '01700000004',
                'emergency_level' => 'critical',
                'status'          => 'approved',
                'approved_at'     => now()->subMinutes(30),
                'notes'           => 'Accident victim. AB- is rare — please help!',
            ],
            [
                'patient_name'    => 'Patient E.',
                'blood_group'     => 'O-',
                'hospital_name'   => 'Square Hospital',
                'hospital_address'=> 'West Panthapath, Dhaka',
                'district'        => 'Dhaka',
                'area'            => 'Panthapath',
                'required_date'   => now()->addDays(4)->format('Y-m-d'),
                'bags_needed'     => 1,
                'contact_number'  => '01700000005',
                'emergency_level' => 'urgent',
                'status'          => 'fulfilled',
                'fulfilled_at'    => now()->subDay(),
                'notes'           => 'Thank you to all donors!',
            ],
        ];

        foreach ($requests as $data) {
            BloodRequest::create(array_merge($data, ['user_id' => $user->id]));
        }
    }
}

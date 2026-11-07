<?php

namespace Database\Seeders;

use App\Models\Testimonial;
use Illuminate\Database\Seeder;

class TestimonialSeeder extends Seeder
{
    public function run(): void
    {
        $testimonials = [
            [
                'author_name' => 'Tanvir Alam',
                'author_role' => 'Voluntary Donor (O+)',
                'content'     => 'Blood Lagbe made it so easy for me to donate blood and directly help people in my city. I found a matching request within hours!',
                'rating'      => 5,
                'is_approved' => true,
            ],
            [
                'author_name' => 'Farhana Mitu',
                'author_role' => 'Patient Family (A+)',
                'content'     => 'My mother needed blood urgently after surgery. Through Blood Lagbe, we found 3 donors in Chattogram within 2 hours. This platform saved her life.',
                'rating'      => 5,
                'is_approved' => true,
            ],
            [
                'author_name' => 'Sabbir Hossain',
                'author_role' => 'Regular Donor (B+)',
                'content'     => 'I have donated 5 times now. Blood Lagbe keeps track of my history and reminds me when I am eligible to donate again. Excellent platform!',
                'rating'      => 5,
                'is_approved' => true,
            ],
            [
                'author_name' => 'Runa Laila',
                'author_role' => 'Recipient Relative (AB-)',
                'content'     => 'We were desperate for AB- blood for my husband\'s emergency surgery. Blood Lagbe found us a donor in our district in just 30 minutes. Truly lifesaving.',
                'rating'      => 5,
                'is_approved' => true,
            ],
            [
                'author_name' => 'Hasan Mahmud',
                'author_role' => 'Youth Volunteer (B-)',
                'content'     => 'As a first-time donor, Blood Lagbe made the process simple and transparent. I knew exactly who I was helping. Highly professional.',
                'rating'      => 5,
                'is_approved' => true,
            ],
            [
                'author_name' => 'Shahida Begum',
                'author_role' => 'Community Member (O-)',
                'content'     => 'The blood request system is exceptionally well-designed. I posted a request and received instant alerts when matching donors were identified.',
                'rating'      => 5,
                'is_approved' => true,
            ],
        ];

        foreach ($testimonials as $data) {
            Testimonial::firstOrCreate(['author_name' => $data['author_name']], $data);
        }
    }
}

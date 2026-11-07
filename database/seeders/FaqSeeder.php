<?php

namespace Database\Seeders;

use App\Models\Faq;
use Illuminate\Database\Seeder;

class FaqSeeder extends Seeder
{
    public function run(): void
    {
        $faqs = [
            [
                'question'   => 'Who can donate blood?',
                'answer'     => 'Generally, healthy adults between 18 and 65 years old who weigh at least 50 kg can donate blood. However, eligibility depends on many factors including medical history. Always consult with a healthcare professional before donating.',
                'sort_order' => 1,
                'is_active'  => true,
            ],
            [
                'question'   => 'How often can I donate blood?',
                'answer'     => 'Whole blood can typically be donated every 90 days (3 months). However, this is a general guideline — your healthcare provider will give you the most accurate advice based on your individual health.',
                'sort_order' => 2,
                'is_active'  => true,
            ],
            [
                'question'   => 'Is my personal information safe on Blood Lagbe?',
                'answer'     => 'Yes. We take privacy very seriously. Your contact number and personal details are only shared with verified users when you choose to respond to a blood request. Your full contact information is never displayed publicly.',
                'sort_order' => 3,
                'is_active'  => true,
            ],
            [
                'question'   => 'How do I register as a blood donor?',
                'answer'     => 'Click "Become a Donor" on the navigation menu, fill in the registration form with your details including blood group and location, and submit. After creating an account, your profile will be visible to people searching for donors.',
                'sort_order' => 4,
                'is_active'  => true,
            ],
            [
                'question'   => 'What happens after I submit a blood request?',
                'answer'     => 'Your request is reviewed by our team and approved within a few hours. Once approved, it is visible to donors who match your blood group and location. You will receive a notification when your request is approved.',
                'sort_order' => 5,
                'is_active'  => true,
            ],
            [
                'question'   => 'Does Blood Lagbe guarantee blood compatibility?',
                'answer'     => 'No. Blood Lagbe facilitates connections between donors and patients. Blood compatibility and medical suitability must always be confirmed by qualified medical professionals at the hospital or blood bank.',
                'sort_order' => 6,
                'is_active'  => true,
            ],
            [
                'question'   => 'Is Blood Lagbe free to use?',
                'answer'     => 'Yes, Blood Lagbe is completely free for both donors and people in need of blood. Our mission is to save lives, not to make a profit.',
                'sort_order' => 7,
                'is_active'  => true,
            ],
            [
                'question'   => 'How can I update my availability status?',
                'answer'     => 'Log in to your dashboard, go to "Donor Profile", and click the "Toggle Availability" button. You can also update your profile details including last donation date at any time.',
                'sort_order' => 8,
                'is_active'  => true,
            ],
        ];

        foreach ($faqs as $faq) {
            Faq::firstOrCreate(['question' => $faq['question']], $faq);
        }
    }
}

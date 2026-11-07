<?php

namespace Tests\Feature;

use App\Models\BloodRequest;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BloodRequestTest extends TestCase
{
    use RefreshDatabase;

    public function test_public_blood_requests_page_displays_approved_requests(): void
    {
        $user = User::factory()->create();

        BloodRequest::create([
            'user_id' => $user->id,
            'patient_name' => 'Farhan Ahmed',
            'blood_group' => 'AB+',
            'hospital_name' => 'Apollo Hospital',
            'hospital_address' => 'Bashundhara, Dhaka',
            'district' => 'Dhaka',
            'required_date' => now()->addDays(2),
            'bags_needed' => 2,
            'contact_number' => '01712345678',
            'emergency_level' => 'urgent',
            'status' => 'approved',
        ]);

        $response = $this->get('/blood-requests');

        $response->assertStatus(200);
        $response->assertSee('Apollo Hospital');
        $response->assertSee('AB+');
    }

    public function test_authenticated_user_can_create_blood_request(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post('/dashboard/requests', [
            'patient_name' => 'Sadia Islam',
            'blood_group' => 'O-',
            'hospital_name' => 'Square Hospital',
            'hospital_address' => 'Panthapath, Dhaka',
            'district' => 'Dhaka',
            'required_date' => now()->addDays(3)->format('Y-m-d'),
            'bags_needed' => 1,
            'contact_number' => '01812345678',
            'emergency_level' => 'critical',
            'notes' => 'Urgent heart surgery transfusion',
        ]);

        $response->assertSessionHas('success');
        $this->assertDatabaseHas('blood_requests', [
            'patient_name' => 'Sadia Islam',
            'blood_group' => 'O-',
            'emergency_level' => 'critical',
            'status' => 'pending',
        ]);
    }

    public function test_blood_request_requires_valid_fields(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post('/dashboard/requests', [
            'patient_name' => '',
            'blood_group' => 'INVALID',
        ]);

        $response->assertSessionHasErrors(['patient_name', 'blood_group', 'hospital_name', 'district', 'required_date']);
    }

    public function test_user_can_cancel_their_own_pending_request(): void
    {
        $user = User::factory()->create();

        $request = BloodRequest::create([
            'user_id' => $user->id,
            'patient_name' => 'Rakib Hasan',
            'blood_group' => 'A+',
            'hospital_name' => 'Labaid Hospital',
            'hospital_address' => 'Dhanmondi, Dhaka',
            'district' => 'Dhaka',
            'required_date' => now()->addDays(2),
            'bags_needed' => 1,
            'contact_number' => '01912345678',
            'emergency_level' => 'normal',
            'status' => 'pending',
        ]);

        $response = $this->actingAs($user)->post("/dashboard/requests/{$request->id}/cancel");

        $response->assertSessionHas('success');
        $this->assertEquals('cancelled', $request->fresh()->status);
    }
}

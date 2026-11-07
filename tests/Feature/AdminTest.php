<?php

namespace Tests\Feature;

use App\Models\BloodRequest;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminTest extends TestCase
{
    use RefreshDatabase;

    public function test_non_admin_cannot_access_admin_dashboard(): void
    {
        $user = User::factory()->create(['role' => 'user']);

        $response = $this->actingAs($user)->get('/admin');

        $response->assertStatus(403);
    }

    public function test_admin_can_access_admin_dashboard(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $response = $this->actingAs($admin)->get('/admin');

        $response->assertStatus(200);
        $response->assertSee('Admin Panel');
    }

    public function test_admin_can_approve_pending_blood_request(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $user = User::factory()->create();

        $request = BloodRequest::create([
            'user_id' => $user->id,
            'patient_name' => 'Monirul Islam',
            'blood_group' => 'B-',
            'hospital_name' => 'Dhaka Medical',
            'hospital_address' => 'Secretariat Road, Dhaka',
            'district' => 'Dhaka',
            'required_date' => now()->addDays(2),
            'bags_needed' => 1,
            'contact_number' => '01711223344',
            'emergency_level' => 'urgent',
            'status' => 'pending',
        ]);

        $response = $this->actingAs($admin)->post("/admin/requests/{$request->id}/approve");

        $response->assertSessionHas('success');
        $this->assertEquals('approved', $request->fresh()->status);
        $this->assertNotNull($request->fresh()->approved_at);
    }

    public function test_admin_can_reject_blood_request_with_reason(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $user = User::factory()->create();

        $request = BloodRequest::create([
            'user_id' => $user->id,
            'patient_name' => 'Test Patient',
            'blood_group' => 'A-',
            'hospital_name' => 'General Hospital',
            'hospital_address' => 'Station Road',
            'district' => 'Sylhet',
            'required_date' => now()->addDays(1),
            'bags_needed' => 2,
            'contact_number' => '01711223355',
            'emergency_level' => 'normal',
            'status' => 'pending',
        ]);

        $response = $this->actingAs($admin)->post("/admin/requests/{$request->id}/reject", [
            'rejection_reason' => 'Contact number is unreachable after multiple attempts.',
        ]);

        $response->assertSessionHas('success');
        $this->assertEquals('rejected', $request->fresh()->status);
        $this->assertEquals('Contact number is unreachable after multiple attempts.', $request->fresh()->rejection_reason);
    }

    public function test_admin_can_toggle_user_active_status(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $user = User::factory()->create(['is_active' => true]);

        $response = $this->actingAs($admin)->post("/admin/users/{$user->id}/toggle-active");

        $response->assertSessionHas('success');
        $this->assertFalse($user->fresh()->is_active);
    }
}

<?php

namespace Tests\Feature;

use App\Models\DonorProfile;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DonorTest extends TestCase
{
    use RefreshDatabase;

    public function test_donor_search_page_loads_successfully(): void
    {
        $response = $this->get('/donors');

        $response->assertStatus(200);
        $response->assertSee('Find Blood Donors');
    }

    public function test_can_filter_donors_by_blood_group(): void
    {
        $donorUser = User::factory()->create(['role' => 'donor', 'district' => 'Dhaka']);
        DonorProfile::create([
            'user_id' => $donorUser->id,
            'blood_group' => 'O+',
            'district' => 'Dhaka',
            'phone' => '01711111111',
            'is_available' => true,
            'is_public' => true,
        ]);

        $response = $this->get('/donors?blood_group=O+');

        $response->assertStatus(200);
        $response->assertSee('O+');
    }

    public function test_authenticated_user_can_register_as_donor(): void
    {
        $user = User::factory()->create(['role' => 'user']);

        $response = $this->actingAs($user)->post('/become-a-donor', [
            'name' => 'Test Donor',
            'email' => $user->email,
            'phone' => '01700000000',
            'blood_group' => 'A+',
            'district' => 'Dhaka',
            'area' => 'Mirpur',
            'is_available' => 1,
            'consent' => 1,
        ]);

        $response->assertRedirect(route('dashboard.donor-profile'));
        $this->assertDatabaseHas('donor_profiles', [
            'user_id' => $user->id,
            'blood_group' => 'A+',
            'district' => 'Dhaka',
        ]);
        $this->assertEquals('donor', $user->fresh()->role);
    }

    public function test_donor_can_toggle_availability(): void
    {
        $user = User::factory()->create(['role' => 'donor']);
        $profile = DonorProfile::create([
            'user_id' => $user->id,
            'blood_group' => 'B+',
            'district' => 'Chattogram',
            'phone' => '01800000000',
            'is_available' => true,
            'is_public' => true,
        ]);

        $response = $this->actingAs($user)->post('/dashboard/donor-profile/toggle-availability');

        $response->assertSessionHas('success');
        $this->assertFalse($profile->fresh()->is_available);
    }
}

<?php

namespace Tests\Feature;

use App\Models\BloodRequest;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PublicPagesTest extends TestCase
{
    use RefreshDatabase;

    public function test_home_page_renders(): void
    {
        $response = $this->get('/');
        $response->assertStatus(200);
        $response->assertSee('Blood Lagbe');
    }

    public function test_about_page_renders(): void
    {
        $response = $this->get('/about');
        $response->assertStatus(200);
        $response->assertSee('Our Mission');
    }

    public function test_contact_page_renders(): void
    {
        $response = $this->get('/contact');
        $response->assertStatus(200);
        $response->assertSee('Send us a Message');
    }

    public function test_contact_form_submits_successfully(): void
    {
        $response = $this->post('/contact', [
            'name' => 'Kazi Nazrul',
            'email' => 'nazrul@example.com',
            'subject' => 'Need emergency assistance',
            'message' => 'Please provide information about blood donation camps in Dhaka.',
        ]);

        $response->assertSessionHas('success');
        $this->assertDatabaseHas('contact_messages', [
            'name' => 'Kazi Nazrul',
            'email' => 'nazrul@example.com',
        ]);
    }

    public function test_health_check_endpoint(): void
    {
        $response = $this->get('/health');
        $response->assertStatus(200);
        $response->assertJson(['status' => 'ok', 'db' => 'connected']);
    }
}

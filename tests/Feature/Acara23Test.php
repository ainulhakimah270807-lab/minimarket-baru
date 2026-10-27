<?php

namespace Tests\Feature;

use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class Acara23Test extends TestCase
{
    use RefreshDatabase;

    public function test_route_service_provider_home_is_dashboard()
    {
        $this->assertEquals('/dashboard', RouteServiceProvider::HOME);
    }

    public function test_guest_sees_guest_status_on_acara23_profile()
    {
        $response = $this->get(route('acara23.profile'));

        $response->assertStatus(200);
        $response->assertSee('Status: Guest (Belum Login)');
    }

    public function test_authenticated_user_sees_their_profile_and_id_on_acara23_profile()
    {
        $user = User::factory()->create([
            'name' => 'Ainul Hakimah',
            'email' => 'ainul@example.com',
        ]);

        $response = $this->actingAs($user)->get(route('acara23.profile'));

        $response->assertStatus(200);
        $response->assertSee('Welcome, Ainul Hakimah');
        $response->assertSee((string) $user->id);
    }

    public function test_recreated_logout_feature_logs_out_user()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post(route('acara23.logout'));

        $response->assertRedirect('/');
        $this->assertGuest();
    }
}

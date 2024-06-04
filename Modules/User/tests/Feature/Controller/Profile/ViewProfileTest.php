<?php

namespace Modules\User\Tests\Feature\Profile;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Modules\User\App\Models\User;
use Modules\User\App\Services\RoleService;
use Tests\TestCase;

class ViewProfileTest extends TestCase
{
    use RefreshDatabase;

    protected $role;

    public function setUp(): void
    {
        parent::setUp();
        $this->role = new RoleService();
    }

    public function test_profile_user_success_displayed(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $response = $this->get("/profile/$user->uuid");

        $response->assertStatus(200);
        $response->assertViewIs('user::layouts.dashboard.profile');
        $response->assertSeeText('Update Your Photo and Personal Details.');

        $user = $response->viewData('user');
        $this->assertNotNull($user);
    }

    public function test_profile_user_failed_because_you_not_logged_in(): void
    {
        $response = $this->get('/profile/ecd27b79-1ade-4dd2-a854-484676770209');
        $response->assertStatus(302);
        $response->assertRedirect('/login');
        $this->assertTrue(session()->has('error'));
        $this->assertEquals('You must log in first!', session('error'));
    }

    public function test_profile_user_failed_because_parameter_is_invalid(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $response = $this->get('/profile/uuid');
        $response->assertStatus(302);
        $response->assertRedirect('/dashboard');
        $this->assertTrue(session()->has('error'));
        $this->assertEquals('Invalid profile data!', session('error'));
    }

    public function test_profile_user_failed_because_user_not_found(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $response = $this->get('/profile/340ce08a-1468-4723-84d7-f985a23cba43');
        $response->assertStatus(302);
        $response->assertRedirect('/dashboard');
        $this->assertTrue(session()->has('error'));
        $this->assertEquals('User not found!', session('error'));
    }

    public function test_profile_user_failed_because_not_your_account(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $client = $this->role->generateUser();
        $response = $this->get("/profile/$client->uuid");
        $response->assertStatus(302);
        $response->assertRedirect('/dashboard');
        $this->assertTrue(session()->has('error'));
        $this->assertEquals('Invalid profile data!', session('error'));
    }
}

<?php

namespace Modules\User\Tests\Feature\Controller\Logout;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Modules\User\App\Models\User;
use Tests\TestCase;

class LogoutUserTest extends TestCase
{
    use RefreshDatabase;

    public function test_logout_user_success(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $response = $this->post('/logout');
        $response->assertStatus(302);
        $response->assertRedirect('/login');
        $this->assertTrue(session()->has('success'));
        $this->assertEquals('Successfully logged out!', session('success'));
    }

    public function test_logout_user_failed_because_you_not_logged_in(): void
    {
        $response = $this->post('/logout');
        $response->assertStatus(302);
        $response->assertRedirect('/login');
        $this->assertTrue(session()->has('error'));
        $this->assertEquals('You must log in first!', session('error'));
    }
}

<?php

namespace Modules\User\Tests\Feature\Controller\Login;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Modules\User\App\Models\User;
use Tests\TestCase;

class LoginUserTest extends TestCase
{
    use RefreshDatabase;

    public function test_login_user_success(): void
    {
        User::factory()->create();

        $response = $this->post('/login', [
            'email' => 'test@gmail.com',
            'password' => '12345678',
        ]);

        $response->assertStatus(302);
        $response->assertRedirect('/dashboard');
        $response->assertSessionHas('success', 'Successfully logged in!');
        $this->assertTrue(session()->has('success'));
        $this->assertEquals('Successfully logged in!', session('success'));
    }

    public function test_login_user_failed_because_form_is_empty(): void
    {
        $response = $this->post('/login', [
            'email' => '',
            'password' => '',
        ]);

        $response->assertStatus(302);
        $this->assertTrue(session()->has('errors'));
    }

    public function test_login_user_failed_because_user_not_found(): void
    {
        $response = $this->post('/login', [
            'email' => 'test@gmail.com',
            'password' => '12345678',
        ]);

        $response->assertStatus(302);
        $response->assertRedirect('/login');
        $this->assertTrue(session()->has('error'));
        $this->assertEquals('Invalid email or password!', session('error'));
    }

    public function test_login_user_failed_because_password_is_wrong(): void
    {
        User::factory()->create();

        $response = $this->post('/login', [
            'email' => 'test@gmail.com',
            'password' => 'password',
        ]);

        $response->assertStatus(302);
        $response->assertRedirect('/login');
        $this->assertTrue(session()->has('error'));
        $this->assertEquals('Invalid email or password!', session('error'));
    }
}

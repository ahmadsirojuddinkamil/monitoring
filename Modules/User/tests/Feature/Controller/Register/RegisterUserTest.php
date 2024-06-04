<?php

namespace Modules\User\Tests\Feature\Controller\Register;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Modules\User\App\Models\User;
use Ramsey\Uuid\Uuid;
use Tests\TestCase;

class RegisterUserTest extends TestCase
{
    use RefreshDatabase;

    public function test_register_user_success(): void
    {
        $response = $this->post('/register', [
            'uuid' => Uuid::uuid4(),
            'username' => 'test',
            'email' => 'test@gmail.com',
            'password' => '12345678',
        ]);

        $response->assertStatus(302);
        $response->assertRedirect('/login');
        $this->assertTrue(session()->has('success'));
        $this->assertEquals('Successfully create user, please login!', session('success'));
    }

    public function test_register_user_failed_because_form_is_empty(): void
    {
        $response = $this->post('/register', [
            'uuid' => Uuid::uuid4(),
            'username' => '',
            'email' => '',
            'password' => '',
        ]);

        $response->assertStatus(302);
        $this->assertTrue(session()->has('errors'));
    }

    public function test_register_user_failed_because_email_already_exists(): void
    {
        User::factory()->create();

        $response = $this->post('/register', [
            'uuid' => Uuid::uuid4(),
            'username' => 'test',
            'email' => 'test@gmail.com',
            'password' => '12345678',
        ]);

        $response->assertStatus(302);
        $this->assertTrue(session()->has('errors'));
    }
}

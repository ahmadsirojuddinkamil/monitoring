<?php

namespace Modules\Connection\tests\Feature\Controller;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Modules\Connection\App\Models\Connection;
use Modules\User\App\Models\User;
use Tests\TestCase;

class StoreConnectionTest extends TestCase
{
    use RefreshDatabase;

    public function test_create_connection_success(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $key = '0b18b3ba1c9a99fd4cd3df8704aa57e63c28585b35d048305640d91a6f5db5a3';

        $response = $this->post('/connection/create', [
            'endpoint' => 'https://endpoint.com/',
            'register' => "https://endpoint.com/register-monitoring/$key",
            'login' => "https://endpoint.com/login-monitoring/$key",
            'get_log' => "https://endpoint.com/logging/$key",
            'get_log_by_type' => "https://endpoint.com/logging/$key/type",
            'get_log_by_time' => "https://endpoint.com/logging/$key/type/time",
            'delete_log' => "https://endpoint.com/logging/$key",
            'delete_log_by_type' => "https://endpoint.com/logging/$key/type",
            'delete_log_by_time' => "https://endpoint.com/logging/$key/type/time",
        ]);

        $response->assertStatus(302);
        $response->assertRedirect('/connection/'.$user->uuid);
        $this->assertTrue(session()->has('success'));
        $this->assertEquals('Success create connection', session('success'));
    }

    public function test_create_connection_failed_because_not_login(): void
    {
        $response = $this->post('/connection/create');
        $response->assertStatus(302);
        $response->assertRedirect('/login');
        $this->assertTrue(session()->has('error'));
        $this->assertEquals('You must log in first!', session('error'));
    }

    public function test_create_connection_failed_because_already_has_a_connection(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $connection = Connection::factory()->create();
        $connection->update([
            'user_uuid' => $user->uuid->toString(),
        ]);

        $key = '0b18b3ba1c9a99fd4cd3df8704aa57e63c28585b35d048305640d91a6f5db5a3';

        $response = $this->post('/connection/create', [
            'endpoint' => 'https://endpoint.com/',
            'register' => "https://endpoint.com/register-monitoring/$key",
            'login' => "https://endpoint.com/login-monitoring/$key",
            'get_log' => "https://endpoint.com/logging/$key",
            'get_log_by_type' => "https://endpoint.com/logging/$key/type",
            'get_log_by_time' => "https://endpoint.com/logging/$key/type/time",
            'delete_log' => "https://endpoint.com/logging/$key",
            'delete_log_by_type' => "https://endpoint.com/logging/$key/type",
            'delete_log_by_time' => "https://endpoint.com/logging/$key/type/time",
        ]);

        $response->assertStatus(404);
    }

    public function test_create_connection_failed_because_form_is_empty(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $response = $this->post('/connection/create', [
            'endpoint' => '',
            'register' => '',
            'login' => '',
            'get_log' => '',
            'get_log_by_type' => '',
            'get_log_by_time' => '',
            'delete_log' => '',
            'delete_log_by_type' => '',
            'delete_log_by_time' => '',
        ]);

        $response->assertStatus(302);
        $this->assertTrue(session()->has('errors'));
    }

    public function test_create_connection_failed_because_endpoint_is_invalid(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $response = $this->post('/connection/create', [
            'endpoint' => 'https://endpoint.com/',
            'register' => 'https://endpoint.com/register-monitoring/KEY',
            'login' => 'https://endpoint.com/login-monitoring/KEY',
            'get_log' => 'https://endpoint.com/logging/KEY',
            'get_log_by_type' => 'https://endpoint.com/logging/KEY/type',
            'get_log_by_time' => 'https://endpoint.com/logging/KEY/type/time',
            'delete_log' => 'https://endpoint.com/logging/KEY',
            'delete_log_by_type' => 'https://endpoint.com/logging/KEY/type',
            'delete_log_by_time' => 'https://endpoint.com/logging/KEY/type/time',
        ]);

        $response->assertStatus(302);
        $response->assertRedirect('/connection/'.$user->uuid);
        $this->assertTrue(session()->has('error'));
        $this->assertEquals('Your endpoint is invalid!', session('error'));
    }
}

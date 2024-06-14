<?php

namespace Modules\Connection\tests\Feature\Controllers;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Modules\Connection\App\Models\Connection;
use Modules\User\App\Models\User;
use Tests\TestCase;

class UpdateTest extends TestCase
{
    use RefreshDatabase;

    public function test_update_connection_success(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $connection = Connection::factory()->create();
        $connection->update([
            'user_uuid' => $user->uuid->toString(),
        ]);

        $key = '0b18b3ba1c9a99fd4cd3df8704aa57e63c28585b35d048305640d91a6f5db5a3';

        $response = $this->put("/connection/$connection->uuid", [
            'endpoint' => 'https://endpoint.com/',
            'register' => "https://endpoint.com/api/register-monitoring/$key",
            'login' => "https://endpoint.com/api/login-monitoring/$key",
            'get_log' => "https://endpoint.com/api/logging/$key",
            'get_log_by_type' => "https://endpoint.com/api/logging/$key/type",
            'get_log_by_time' => "https://endpoint.com/api/logging/$key/type/time",
            'delete_log' => "https://endpoint.com/api/logging/$key",
            'delete_log_by_type' => "https://endpoint.com/api/logging/$key/type",
            'delete_log_by_time' => "https://endpoint.com/api/logging/$key/type/time",
            'token' => '0b18b3ba1c9a99fd4cd3df8704aa57e63c28585b35d048305640d91a6f5db5a3',
        ]);
        $response->assertStatus(302);
        $response->assertRedirect('/connection/'.$user->uuid);
        $this->assertTrue(session()->has('success'));
        $this->assertEquals('Success update connection', session('success'));
    }

    public function test_update_connection_failed_because_not_login(): void
    {
        $response = $this->put('/connection/fbc0e78e-a806-4455-a8db-82e617f94ae3');
        $response->assertStatus(302);
        $response->assertRedirect('/login');
        $this->assertTrue(session()->has('error'));
        $this->assertEquals('You must log in first!', session('error'));
    }

    public function test_update_connection_failed_because_it_not_have_a_connection(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $key = '0b18b3ba1c9a99fd4cd3df8704aa57e63c28585b35d048305640d91a6f5db5a3';

        $response = $this->put('/connection/050c6c5d-8925-43eb-a09e-43c63d9a00e7', [
            'endpoint' => 'https://endpoint.com/',
            'register' => "https://endpoint.com/register-monitoring/$key",
            'login' => "https://endpoint.com/login-monitoring/$key",
            'get_log' => "https://endpoint.com/logging/$key",
            'get_log_by_type' => "https://endpoint.com/logging/$key/type",
            'get_log_by_time' => "https://endpoint.com/logging/$key/type/time",
            'delete_log' => "https://endpoint.com/logging/$key",
            'delete_log_by_type' => "https://endpoint.com/logging/$key/type",
            'delete_log_by_time' => "https://endpoint.com/logging/$key/type/time",
            'token' => '0b18b3ba1c9a99fd4cd3df8704aa57e63c28585b35d048305640d91a6f5db5a3',
        ]);
        $response->assertStatus(404);
    }

    public function test_update_connection_failed_because_form_is_empty(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $response = $this->put('/connection/050c6c5d-8925-43eb-a09e-43c63d9a00e7', [
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

    public function test_update_connection_failed_because_uuid_is_invalid(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $connection = Connection::factory()->create();
        $connection->update([
            'user_uuid' => $user->uuid->toString(),
        ]);

        $response = $this->put('/connection/uuid', [
            'endpoint' => 'https://endpoint.com/',
            'register' => 'https://endpoint.com/register-monitoring/KEY',
            'login' => 'https://endpoint.com/login-monitoring/KEY',
            'get_log' => 'https://endpoint.com/logging/KEY',
            'get_log_by_type' => 'https://endpoint.com/logging/KEY/type',
            'get_log_by_time' => 'https://endpoint.com/logging/KEY/type/time',
            'delete_log' => 'https://endpoint.com/logging/KEY',
            'delete_log_by_type' => 'https://endpoint.com/logging/KEY/type',
            'delete_log_by_time' => 'https://endpoint.com/logging/KEY/type/time',
            'token' => '0b18b3ba1c9a99fd4cd3df8704aa57e63c28585b35d048305640d91a6f5db5a3',
        ]);

        $response->assertStatus(302);
        $response->assertRedirect('/connection/'.$user->uuid);
        $this->assertTrue(session()->has('error'));
        $this->assertEquals('Invalid connection data!', session('error'));
    }

    public function test_update_connection_failed_because_connection_not_found(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $connection = Connection::factory()->create();
        $connection->update([
            'user_uuid' => $user->uuid->toString(),
        ]);

        $response = $this->put('/connection/6762e87c-cdff-4370-ba09-9cdb4c6ec182', [
            'endpoint' => 'https://endpoint.com/',
            'register' => 'https://endpoint.com/register-monitoring/KEY',
            'login' => 'https://endpoint.com/login-monitoring/KEY',
            'get_log' => 'https://endpoint.com/logging/KEY',
            'get_log_by_type' => 'https://endpoint.com/logging/KEY/type',
            'get_log_by_time' => 'https://endpoint.com/logging/KEY/type/time',
            'delete_log' => 'https://endpoint.com/logging/KEY',
            'delete_log_by_type' => 'https://endpoint.com/logging/KEY/type',
            'delete_log_by_time' => 'https://endpoint.com/logging/KEY/type/time',
            'token' => '0b18b3ba1c9a99fd4cd3df8704aa57e63c28585b35d048305640d91a6f5db5a3',
        ]);
        $response->assertStatus(404);
    }

    public function test_update_connection_failed_because_connection_is_not_an_access_right(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $connection = Connection::factory()->create();
        $connection->update([
            'user_uuid' => $user->uuid->toString(),
        ]);

        $client = Connection::clientFactory()->create();

        $response = $this->put("/connection/$client->uuid", [
            'endpoint' => 'https://endpoint.com/',
            'register' => 'https://endpoint.com/register-monitoring/KEY',
            'login' => 'https://endpoint.com/login-monitoring/KEY',
            'get_log' => 'https://endpoint.com/logging/KEY',
            'get_log_by_type' => 'https://endpoint.com/logging/KEY/type',
            'get_log_by_time' => 'https://endpoint.com/logging/KEY/type/time',
            'delete_log' => 'https://endpoint.com/logging/KEY',
            'delete_log_by_type' => 'https://endpoint.com/logging/KEY/type',
            'delete_log_by_time' => 'https://endpoint.com/logging/KEY/type/time',
            'token' => '0b18b3ba1c9a99fd4cd3df8704aa57e63c28585b35d048305640d91a6f5db5a3',
        ]);
        $response->assertStatus(404);
    }

    public function test_update_connection_failed_because_endpoint_is_invalid(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $connection = Connection::factory()->create();
        $connection->update([
            'user_uuid' => $user->uuid->toString(),
        ]);

        $response = $this->put("/connection/$connection->uuid", [
            'endpoint' => 'https://client.com/',
            'register' => 'https://client.com/register-monitoring/KEY',
            'login' => 'https://client.com/login-monitoring/KEY',
            'get_log' => 'https://client.com/logging/KEY',
            'get_log_by_type' => 'https://client.com/logging/KEY/type',
            'get_log_by_time' => 'https://client.com/logging/KEY/type/time',
            'delete_log' => 'https://client.com/logging/KEY',
            'delete_log_by_type' => 'https://client.com/logging/KEY/type',
            'delete_log_by_time' => 'https://client.com/logging/KEY/type/time',
            'token' => '0b18b3ba1c9a99fd4cd3df8704aa57e63c28585b35d048305640d91a6f5db5a3',
        ]);

        $response->assertStatus(302);
        $response->assertRedirect('/connection/'.$user->uuid);
        $this->assertTrue(session()->has('error'));
        $this->assertEquals('Your endpoint is invalid!', session('error'));
    }
}

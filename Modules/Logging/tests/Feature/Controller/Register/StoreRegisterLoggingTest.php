<?php

namespace Modules\Logging\tests\Feature\Controller\Register;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Modules\Connection\App\Models\Connection;
use Modules\Logging\App\Services\LoggingService;
use Modules\User\App\Models\User;
use Tests\TestCase;

class StoreRegisterLoggingTest extends TestCase
{
    use RefreshDatabase;

    protected $logging;

    public function setUp(): void
    {
        parent::setUp();
        $this->logging = new LoggingService();
    }

    public function test_store_register_account_log_success(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $connection = Connection::factory()->create([
            'user_uuid' => $user->uuid,
        ]);

        $validateData = [
            'name' => 'user',
            'email' => 'user@gmail.com',
            'password' => '12345678',
        ];

        Http::fake([
            $connection->register => Http::response([
                'data' => [
                    'name' => $validateData['name'],
                    'email' => $validateData['email'],
                ],
            ], 200, ['Authorization' => 'Bearer '.$connection->token]),
        ]);

        $response = $this->post('/logging/register', $validateData);
        $response->assertStatus(302);
        $response->assertRedirect('/logging/login');
    }

    public function test_store_register_account_log_failed_because_not_login(): void
    {
        $response = $this->post('/logging/register');
        $response->assertStatus(302);
        $response->assertRedirect('/login');
        $this->assertTrue(session()->has('error'));
        $this->assertEquals('You must log in first!', session('error'));
    }

    public function test_store_register_account_log_failed_because_connection_not_found(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $response = $this->post('/logging/register');
        $response->assertStatus(302);
        $response->assertRedirect("connection/$user->uuid");
        $this->assertTrue(session()->has('error'));
        $this->assertEquals('Dont have a connection account yet! Register immediately', session('error'));
    }

    public function test_store_register_account_log_failed_because_form_is_empty(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $connection = Connection::factory()->create([
            'user_uuid' => $user->uuid,
        ]);

        $validateData = [
            'name' => '',
            'email' => '',
            'password' => '',
        ];

        Http::fake([
            $connection->register => Http::response([
                'data' => [
                    'name' => $validateData['name'],
                    'email' => $validateData['email'],
                ],
            ], 200, ['Authorization' => 'Bearer '.$connection->token]),
        ]);

        $response = $this->post('/logging/register', $validateData);
        $response->assertStatus(302);
        $this->assertTrue(session()->has('errors'));
    }

    public function test_store_register_account_log_failed_because_user_already_exists(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $connection = Connection::factory()->create([
            'user_uuid' => $user->uuid,
        ]);

        $validateData = [
            'name' => 'user',
            'email' => 'user@gmail.com',
            'password' => '12345678',
        ];

        Http::fake([
            $connection->register => Http::response([
                'error' => 'User already in use!',
            ], 409),
        ]);

        $response = $this->post('/logging/register', $validateData);
        $response->assertStatus(302);
        $response->assertRedirect('/logging/register');
        $response->assertSessionHas('error', 'User already in use!');
    }
}

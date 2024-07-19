<?php

namespace Modules\Logging\tests\Feature\Controller\Login;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Modules\Connection\App\Models\Connection;
use Modules\Logging\App\Services\LoggingService;
use Modules\User\App\Models\User;
use Tests\TestCase;

class StoreLoginLoggingTest extends TestCase
{
    use RefreshDatabase;

    protected $logging;

    public function setUp(): void
    {
        parent::setUp();
        $this->logging = new LoggingService();
    }

    public function test_store_login_account_log_success(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $connection = Connection::factory()->create([
            'user_uuid' => $user->uuid,
        ]);

        $validateData = [
            'email' => 'user@gmail.com',
            'password' => '12345678',
        ];

        $jwt = 'eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJzdWIiOiIxMjM0NTY3ODkwIiwibmFtZSI6IkpvaG4gRG9lIiwiaWF0IjoxNTE2MjM5MDIyfQ.SflKxwRJSMeKKF2QT4fwpMeJf36POk6yJV_adQssw5c';

        Http::fake([
            $connection->login => Http::response([
                'data' => $jwt,
            ], 200, ['Authorization' => 'Bearer '.$connection->token]),
        ]);

        $response = $this->post('/logging/login', $validateData);
        $response->assertStatus(302);
        $response->assertRedirect("/logging/$user->uuid/create");
    }

    public function test_store_login_account_log_failed_because_not_login(): void
    {
        $response = $this->post('/logging/login');
        $response->assertStatus(302);
        $response->assertRedirect('/login');
        $this->assertTrue(session()->has('error'));
        $this->assertEquals('You must log in first!', session('error'));
    }

    public function test_store_login_account_log_failed_because_connection_not_found(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $response = $this->post('/logging/login');
        $response->assertStatus(302);
        $response->assertRedirect("connection/$user->uuid");
        $this->assertTrue(session()->has('error'));
        $this->assertEquals('Dont have a connection account yet! Register immediately', session('error'));
    }

    public function test_store_login_account_log_failed_because_form_is_empty(): void
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

        $jwt = 'eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJzdWIiOiIxMjM0NTY3ODkwIiwibmFtZSI6IkpvaG4gRG9lIiwiaWF0IjoxNTE2MjM5MDIyfQ.SflKxwRJSMeKKF2QT4fwpMeJf36POk6yJV_adQssw5c';

        Http::fake([
            $connection->login => Http::response([
                'data' => $jwt,
            ], 200, ['Authorization' => 'Bearer '.$connection->token]),
        ]);

        $response = $this->post('/logging/login', $validateData);
        $response->assertStatus(302);
        $this->assertTrue(session()->has('errors'));
    }

    public function test_store_login_account_log_failed_because_user_not_found(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $connection = Connection::factory()->create([
            'user_uuid' => $user->uuid,
        ]);

        $validateData = [
            'email' => 'user@gmail.com',
            'password' => '12345678',
        ];

        Http::fake(function ($request) use ($connection, $validateData) {
            if ($request->url() == $connection->login) {
                if ($request['email'] === $validateData['email'] && $request['password'] === $validateData['password']) {
                    $jwt = 'eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJzdWIiOiIxMjM0NTY3ODkwIiwibmFtZSI6IkpvaG4gRG9lIiwiaWF0IjoxNTE2MjM5MDIyfQ.SflKxwRJSMeKKF2QT4fwpMeJf36POk6yJV_adQssw5c';

                    return Http::response([
                        'data' => $jwt,
                    ], 200, ['Authorization' => 'Bearer '.$connection->token]);
                } else {
                    return Http::response(['error' => 'User not found!'], 404);
                }
            }

            return Http::response([], 404);
        });

        $response = $this->post('/logging/login', [
            'email' => 'wrongemail@gmail.com',
            'password' => 'wrongpassword',
        ]);

        $response->assertStatus(302);
        $response->assertRedirect('/logging/login');
        $response->assertSessionHas('error', 'User not found!');
    }
}

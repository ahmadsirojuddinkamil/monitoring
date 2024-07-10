<?php

namespace Modules\Logging\tests\Feature\Controller\Fetch;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Modules\Connection\App\Models\Connection;
use Modules\Logging\App\Services\LoggingService;
use Modules\User\App\Models\User;
use Tests\TestCase;

class FetchLogErrorTest extends TestCase
{
    use RefreshDatabase;

    protected $logging;

    public function setUp(): void
    {
        parent::setUp();
        $this->logging = new LoggingService();
    }

    public function test_fetch_log_failed_because_not_login(): void
    {
        $response = $this->post('/logging/3265ede0-3b61-49bb-a9d9-6a63a3fc31f2/store');
        $response->assertStatus(302);
        $response->assertRedirect('/login');
        $this->assertTrue(session()->has('error'));
        $this->assertEquals('You must log in first!', session('error'));
    }

    public function test_fetch_log_failed_because_connection_not_found(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $response = $this->post('/logging/3265ede0-3b61-49bb-a9d9-6a63a3fc31f2/store');
        $response->assertStatus(302);
        $response->assertRedirect("connection/$user->uuid");
        $this->assertTrue(session()->has('error'));
        $this->assertEquals('Dont have a connection account yet! Register immediately', session('error'));
    }

    public function test_fetch_log_failed_because_jwt_token_is_empty(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        Connection::factory()->create([
            'user_uuid' => $user->uuid,
        ]);

        $jwtToken = 'eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJzdWIiOiIxMjM0NTY3ODkwIiwibmFtZSI6IkpvaG4gRG9lIiwiaWF0IjoxNTE2MjM5MDIyfQ.SflKxwRJSMeKKF2QT4fwpMeJf36POk6yJV_adQssw5c';

        $endpoint = 'https://endpoint.com/api/logging/9b68f7dbc448fe1aeff1a48635e4ded8bd232d887908c1b7283093cd40287d69';

        Http::fake([
            $endpoint => Http::response([
                'data' => [
                    'testing' => [
                        'info' => [
                            '[2024-06-03 07:31:25] testing.INFO: Success get log',
                        ],
                    ],
                ],
            ], 200, ['Authorization' => 'Bearer '.$jwtToken]),
        ]);

        $response = $this->withCookie('jwt_token', '')->post("/logging/$user->uuid/store", [
            'type' => 'get_log',
        ]);

        $response->assertStatus(302);
        $response->assertRedirect('/logging/login');
        $this->assertTrue(session()->has('error'));
        $this->assertEquals('You not logged in yet!', session('error'));
    }

    public function test_fetch_log_failed_because_form_is_empty(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        Connection::factory()->create([
            'user_uuid' => $user->uuid,
        ]);

        $jwtToken = 'eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJzdWIiOiIxMjM0NTY3ODkwIiwibmFtZSI6IkpvaG4gRG9lIiwiaWF0IjoxNTE2MjM5MDIyfQ.SflKxwRJSMeKKF2QT4fwpMeJf36POk6yJV_adQssw5c';

        $endpoint = 'https://endpoint.com/api/logging/9b68f7dbc448fe1aeff1a48635e4ded8bd232d887908c1b7283093cd40287d69';

        Http::fake([
            $endpoint => Http::response([
                'data' => [
                    'testing' => [
                        'info' => [
                            '[2024-06-03 07:31:25] testing.INFO: Success get log',
                        ],
                    ],
                ],
            ], 200, ['Authorization' => 'Bearer '.$jwtToken]),
        ]);

        $response = $this->withCookie('jwt_token', $jwtToken)->post("/logging/$user->uuid/store", [
            'type' => '',
        ]);

        $response->assertStatus(302);
        $this->assertTrue(session()->has('errors'));
    }

    public function test_fetch_log_failed_because_invalid_jwt_format(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        Connection::factory()->create([
            'user_uuid' => $user->uuid,
        ]);

        $jwtToken = 'eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJzdWIiOiIxMjM0NTY3ODkwIiwibmFtZSI6IkpvaG4gRG9lIiwiaWF0IjoxNTE2MjM5MDIyfQ.SflKxwRJSMeKKF2QT4fwpMeJf36POk6yJV_adQssw5c';

        $endpoint = 'https://endpoint.com/api/logging/9b68f7dbc448fe1aeff1a48635e4ded8bd232d887908c1b7283093cd40287d69';

        Http::fake([
            $endpoint => Http::response([
                'error' => 'Invalid JWT format!',
            ], 401, ['Authorization' => 'Bearer '.$jwtToken]),
        ]);

        $response = $this->withCookie('jwt_token', 'token')->post("/logging/$user->uuid/store", [
            'type' => 'get_log',
        ]);

        $response->assertStatus(302);
        $response->assertRedirect("/logging/$user->uuid");
        $this->assertTrue(session()->has('error'));
        $this->assertEquals('Invalid JWT format!', session('error'));

    }

    public function test_fetch_get_log_failed_because_token_is_expired(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        Connection::factory()->create([
            'user_uuid' => $user->uuid,
        ]);

        $jwtToken = 'eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJzdWIiOiIxMjM0NTY3ODkwIiwibmFtZSI6IkpvaG4gRG9lIiwiaWF0IjoxNTE2MjM5MDIyfQ.SflKxwRJSMeKKF2QT4fwpMeJf36POk6yJV_adQssw5c';

        $endpoint = 'https://endpoint.com/api/logging/9b68f7dbc448fe1aeff1a48635e4ded8bd232d887908c1b7283093cd40287d69';

        Http::fake([
            $endpoint => Http::response([
                'error' => 'Token is expired',
            ], 401, ['Authorization' => 'Bearer '.$jwtToken]),
        ]);

        $response = $this->withCookie('jwt_token', $jwtToken)->post("/logging/$user->uuid/store", [
            'type' => 'get_log',
        ]);

        $response->assertStatus(302);
        $response->assertRedirect("/logging/$user->uuid");
        $this->assertTrue(session()->has('error'));
        $this->assertEquals('Token is expired', session('error'));

        $logPath = storage_path('logs/laravel.log');
        $logContents = file_get_contents($logPath);
        preg_match('/directory testing, ([a-f0-9\-]+)/', $logContents, $matches);
        $uuidFromLog = $matches[1] ?? null;
        $this->assertNotNull($uuidFromLog);
        Storage::deleteDirectory("public/get_log/{$uuidFromLog}");
        file_put_contents($logPath, preg_replace("/\[.*\] testing.INFO: directory testing, $uuidFromLog\s*/", '', $logContents));
    }
}

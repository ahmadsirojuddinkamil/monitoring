<?php

namespace Modules\Logging\tests\Feature\Controller\Fetch;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Modules\Connection\App\Models\Connection;
use Modules\Logging\App\Services\LoggingService;
use Modules\User\App\Models\User;
use Tests\TestCase;

class FetchLogTest extends TestCase
{
    use RefreshDatabase;

    protected $logging;

    public function setUp(): void
    {
        parent::setUp();
        $this->logging = new LoggingService();
    }

    public function test_fetch_get_log_success(): void
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
            'type' => 'get_log',
        ]);

        $response->assertStatus(302);
        $response->assertRedirect("/logging/$user->uuid");
        $this->assertTrue(session()->has('success'));
        $this->assertEquals('Successfully perform log operations', session('success'));

        $logPath = storage_path('logs/laravel.log');
        $logContents = file_get_contents($logPath);
        preg_match('/directory testing, ([a-f0-9\-]+)/', $logContents, $matches);
        $uuidFromLog = $matches[1] ?? null;
        $this->assertNotNull($uuidFromLog);
        Storage::deleteDirectory("public/get_log/{$uuidFromLog}");
        file_put_contents($logPath, preg_replace("/\[.*\] testing.INFO: directory testing, $uuidFromLog\s*/", '', $logContents));
    }

    public function test_fetch_get_log_by_type_success(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        Connection::factory()->create([
            'user_uuid' => $user->uuid,
        ]);

        $jwtToken = 'eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJzdWIiOiIxMjM0NTY3ODkwIiwibmFtZSI6IkpvaG4gRG9lIiwiaWF0IjoxNTE2MjM5MDIyfQ.SflKxwRJSMeKKF2QT4fwpMeJf36POk6yJV_adQssw5c';

        $endpoint = 'https://endpoint.com/api/logging/30ba2ed1133828e02aa01e59821dbef205ab5ea9c06cb9d097bdfd3ac34533c3/type';

        Http::fake([
            $endpoint => Http::response([
                'data' => [
                    'testing' => [
                        'info' => [
                            '[2024-06-03 07:31:25] testing.INFO: Success get log by type',
                        ],
                    ],
                ],
            ], 200, ['Authorization' => 'Bearer '.$jwtToken]),
        ]);

        $response = $this->withCookie('jwt_token', $jwtToken)->post("/logging/$user->uuid/store", [
            'type' => 'get_log_by_type',
            'type_env' => 'testing',
        ]);

        $response->assertStatus(302);
        $response->assertRedirect("/logging/$user->uuid");
        $this->assertTrue(session()->has('success'));
        $this->assertEquals('Successfully perform log operations', session('success'));

        $logPath = storage_path('logs/laravel.log');
        $logContents = file_get_contents($logPath);
        preg_match('/directory testing, ([a-f0-9\-]+)/', $logContents, $matches);
        $uuidFromLog = $matches[1] ?? null;
        $this->assertNotNull($uuidFromLog);
        Storage::deleteDirectory("public/get_log_by_type/{$uuidFromLog}");
        file_put_contents($logPath, preg_replace("/\[.*\] testing.INFO: directory testing, $uuidFromLog\s*/", '', $logContents));
    }
}

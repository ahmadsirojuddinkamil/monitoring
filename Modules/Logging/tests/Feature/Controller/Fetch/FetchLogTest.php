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
        $this->assertEquals('Successfully get log', session('success'));

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
        $this->assertEquals('Successfully get log by type: testing', session('success'));

        $logPath = storage_path('logs/laravel.log');
        $logContents = file_get_contents($logPath);
        preg_match('/directory testing, ([a-f0-9\-]+)/', $logContents, $matches);
        $uuidFromLog = $matches[1] ?? null;
        $this->assertNotNull($uuidFromLog);
        Storage::deleteDirectory("public/get_log_by_type/{$uuidFromLog}");
        file_put_contents($logPath, preg_replace("/\[.*\] testing.INFO: directory testing, $uuidFromLog\s*/", '', $logContents));
    }

    public function test_fetch_get_log_by_time_success(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        Connection::factory()->create([
            'user_uuid' => $user->uuid,
        ]);

        $jwtToken = 'eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJzdWIiOiIxMjM0NTY3ODkwIiwibmFtZSI6IkpvaG4gRG9lIiwiaWF0IjoxNTE2MjM5MDIyfQ.SflKxwRJSMeKKF2QT4fwpMeJf36POk6yJV_adQssw5c';

        $endpoint = 'https://endpoint.com/api/logging/082534e1184185f7cd56287a6d6b0864d0274c4d33d5629ebb21a064987977d3/type/time';

        Http::fake([
            $endpoint => Http::response([
                'data' => [
                    'testing' => [
                        'info' => [
                            '[2024-07-10 07:31:25] testing.INFO: Success get log by time',
                        ],
                    ],
                ],
            ], 200, ['Authorization' => 'Bearer '.$jwtToken]),
        ]);

        $response = $this->withCookie('jwt_token', $jwtToken)->post("/logging/$user->uuid/store", [
            'type' => 'get_log_by_time',
            'type_env' => 'testing',
            'time_start' => '2024-07-01T11:11:11',
            'time_end' => '2024-07-31T22:22:22',
        ]);

        $response->assertStatus(302);
        $response->assertRedirect("/logging/$user->uuid");
        $this->assertTrue(session()->has('success'));
        $this->assertEquals('Successfully get log by type: testing, range time: 01 July 2024, 11:11 - 31 July 2024, 22:22', session('success'));

        $logPath = storage_path('logs/laravel.log');
        $logContents = file_get_contents($logPath);
        preg_match('/directory testing, ([a-f0-9\-]+)/', $logContents, $matches);
        $uuidFromLog = $matches[1] ?? null;
        $this->assertNotNull($uuidFromLog);
        Storage::deleteDirectory("public/get_log_by_time/{$uuidFromLog}");
        file_put_contents($logPath, preg_replace("/\[.*\] testing.INFO: directory testing, $uuidFromLog\s*/", '', $logContents));
    }

    public function test_fetch_delete_log_success(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        Connection::factory()->create([
            'user_uuid' => $user->uuid,
        ]);

        $jwtToken = 'eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJzdWIiOiIxMjM0NTY3ODkwIiwibmFtZSI6IkpvaG4gRG9lIiwiaWF0IjoxNTE2MjM5MDIyfQ.SflKxwRJSMeKKF2QT4fwpMeJf36POk6yJV_adQssw5c';

        $endpoint = 'https://endpoint.com/api/logging/0276ca0ac9d0e8442b5640072eba90f23d4c20054dc02429c9cbed60a98660b0';

        Http::fake([
            $endpoint => Http::response([
                'data' => 'all data in log deleted successfully.',
            ], 200, ['Authorization' => 'Bearer '.$jwtToken]),
        ]);

        $response = $this->withCookie('jwt_token', $jwtToken)->post("/logging/$user->uuid/store", [
            'type' => 'delete_log',
        ]);

        $response->assertStatus(302);
        $response->assertRedirect("/logging/$user->uuid");
        $this->assertTrue(session()->has('success'));
        $this->assertEquals('Successfully delete log', session('success'));
    }

    public function test_fetch_delete_log_by_type_success(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        Connection::factory()->create([
            'user_uuid' => $user->uuid,
        ]);

        $jwtToken = 'eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJzdWIiOiIxMjM0NTY3ODkwIiwibmFtZSI6IkpvaG4gRG9lIiwiaWF0IjoxNTE2MjM5MDIyfQ.SflKxwRJSMeKKF2QT4fwpMeJf36POk6yJV_adQssw5c';

        $endpoint = 'https://endpoint.com/api/logging/e5906be35c1a3ffbe05bd706b03f6ef7685e8e6be47b1f59d3b480303602cad6/type';

        Http::fake([
            $endpoint => Http::response([
                'data' => 'success delete data logging by type: testing',
            ], 200, ['Authorization' => 'Bearer '.$jwtToken]),
        ]);

        $response = $this->withCookie('jwt_token', $jwtToken)->post("/logging/$user->uuid/store", [
            'type' => 'delete_log_by_type',
            'type_env' => 'testing',
        ]);

        $response->assertStatus(302);
        $response->assertRedirect("/logging/$user->uuid");
        $this->assertTrue(session()->has('success'));
        $this->assertEquals('Successfully delete log by type: testing', session('success'));
    }

    public function test_fetch_delete_log_by_time_success(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        Connection::factory()->create([
            'user_uuid' => $user->uuid,
        ]);

        $jwtToken = 'eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJzdWIiOiIxMjM0NTY3ODkwIiwibmFtZSI6IkpvaG4gRG9lIiwiaWF0IjoxNTE2MjM5MDIyfQ.SflKxwRJSMeKKF2QT4fwpMeJf36POk6yJV_adQssw5c';

        $endpoint = 'https://endpoint.com/api/logging/05bd300fdc5781213f29f05a6f99c7b74f2eb4a5b3dfc509c9387742de180d36/type/time';

        Http::fake([
            $endpoint => Http::response([
                'data' => 'success delete data logging by type: testing and time: 2024-07-01T11:11:11 - 2024-07-31T22:22:22',
            ], 200, ['Authorization' => 'Bearer '.$jwtToken]),
        ]);

        $response = $this->withCookie('jwt_token', $jwtToken)->post("/logging/$user->uuid/store", [
            'type' => 'delete_log_by_time',
            'type_env' => 'testing',
            'time_start' => '2024-07-01T11:11:11',
            'time_end' => '2024-07-31T22:22:22',
        ]);

        $response->assertStatus(302);
        $response->assertRedirect("/logging/$user->uuid");
        $this->assertTrue(session()->has('success'));
        $this->assertEquals('Successfully delete log by type: testing, range time: 01 July 2024, 11:11 - 31 July 2024, 22:22', session('success'));
    }
}

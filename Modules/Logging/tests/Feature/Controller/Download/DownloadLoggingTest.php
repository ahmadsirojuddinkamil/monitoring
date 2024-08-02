<?php

namespace Modules\Logging\tests\Feature\Controller;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Modules\Logging\App\Models\Logging;
use Modules\Logging\App\Services\LoggingService;
use Modules\User\App\Models\User;
use Tests\TestCase;

class DownloadLoggingTest extends TestCase
{
    use RefreshDatabase;

    protected $logging;

    public function setUp(): void
    {
        parent::setUp();
        $this->logging = new LoggingService();
    }

    public function test_download_logging_success(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $log = $this->logging->generateConnectionLog($user);
        $this->logging->generateFileExcel();

        $response = $this->get("/logging/$log->uuid/error/download");
        $response->assertStatus(200);
        $response->assertHeader('content-type', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');

        $this->logging->deleteFileExcel();
    }

    public function test_download_logging_failed_because_not_login(): void
    {
        $response = $this->get('/logging/9a3c3d3e-40ac-43f6-95d3-31f2e3687c7c/error/download');
        $response->assertStatus(302);
        $response->assertRedirect('/login');
        $this->assertTrue(session()->has('error'));
        $this->assertEquals('You must log in first!', session('error'));
    }

    public function test_download_logging_failed_because_connection_not_found(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $response = $this->get('/logging/9a3c3d3e-40ac-43f6-95d3-31f2e3687c7c/error/download');
        $response->assertStatus(302);
        $response->assertRedirect('/connection/'.$user->uuid);
        $this->assertTrue(session()->has('error'));
        $this->assertEquals('Dont have a connection account yet! Register immediately', session('error'));
    }

    public function test_download_logging_failed_because_uuid_is_invalid(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $this->logging->generateConnectionLog($user);

        $response = $this->get('/logging/uuid/error/download');
        $response->assertStatus(404);
    }

    public function test_download_logging_failed_because_type_is_invalid(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $this->logging->generateConnectionLog($user);

        $response = $this->get('/logging/9a3c3d3e-40ac-43f6-95d3-31f2e3687c7c/error/download');
        $response->assertStatus(404);
    }

    public function test_download_logging_failed_because_log_not_found(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $this->logging->generateConnectionLog($user);

        $response = $this->get('/logging/38df4212-1c35-43c2-bb6b-80c47a15d673/error/download');
        $response->assertStatus(404);
    }

    public function test_download_logging_failed_because_log_is_not_an_access_right(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $this->logging->generateConnectionLog($user);

        $client = Logging::factory()->create();

        $response = $this->get("/logging/$client->uuid/error/download");
        $response->assertStatus(404);
    }
}

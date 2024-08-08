<?php

namespace Modules\Logging\tests\Feature\Controller;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Modules\Logging\App\Models\Logging;
use Modules\Logging\App\Services\LoggingService;
use Modules\User\App\Models\User;
use Tests\TestCase;

class DeleteLoggingTest extends TestCase
{
    use RefreshDatabase;

    protected $logging;

    public function setUp(): void
    {
        parent::setUp();
        $this->logging = new LoggingService();
    }

    public function test_delete_logging_success(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $log = $this->logging->generateConnectionLog($user);
        $this->logging->generateFileExcel();

        $response = $this->delete("/logging/$log->uuid/delete");
        $response->assertStatus(302);
        $response->assertRedirect('/logging/'.$user->uuid);
        $this->assertTrue(session()->has('success'));
        $this->assertEquals('Success deleted your log!', session('success'));

        $this->logging->deleteFileExcel();
    }

    public function test_delete_logging_failed_because_not_login(): void
    {
        $response = $this->delete('/logging/9a3c3d3e-40ac-43f6-95d3-31f2e3687c7c/delete');
        $response->assertStatus(302);
        $response->assertRedirect('/login');
        $this->assertTrue(session()->has('error'));
        $this->assertEquals('You must log in first!', session('error'));
    }

    public function test_delete_logging_failed_because_connection_not_found(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $response = $this->delete('/logging/9a3c3d3e-40ac-43f6-95d3-31f2e3687c7c/delete');
        $response->assertStatus(302);
        $response->assertRedirect('/connection/'.$user->uuid);
        $this->assertTrue(session()->has('error'));
        $this->assertEquals('Dont have a connection account yet! Register immediately', session('error'));
    }

    public function test_delete_logging_failed_because_uuid_is_invalid(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $this->logging->generateConnectionLog($user);

        $response = $this->delete('/logging/uuid/delete');
        $response->assertStatus(404);
    }

    public function test_delete_logging_failed_because_log_not_found(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $this->logging->generateConnectionLog($user);

        $response = $this->delete('/logging/9a3c3d3e-40ac-43f6-95d3-31f2e3687c7c/delete');
        $response->assertStatus(404);
    }

    public function test_delete_logging_failed_because_log_is_not_an_access_right(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $this->logging->generateConnectionLog($user);

        $client = Logging::factory()->create();

        $response = $this->delete("/logging/$client->uuid/delete");
        $response->assertStatus(404);
    }
}

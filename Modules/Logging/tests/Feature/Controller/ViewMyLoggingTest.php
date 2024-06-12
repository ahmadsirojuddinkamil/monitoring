<?php

namespace Modules\Logging\tests\Feature\Controller;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Modules\Logging\App\Services\LoggingService;
use Modules\User\App\Models\User;
use Tests\TestCase;

class ViewMyLoggingTest extends TestCase
{
    use RefreshDatabase;

    protected $logging;

    public function setUp(): void
    {
        parent::setUp();
        $this->logging = new LoggingService();
    }

    public function test_view_my_logging_success_displayed(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $this->logging->generateConnectionLog($user);

        $response = $this->get("/logging/$user->uuid");
        $response->assertStatus(200);
        $response->assertSeeText('LOG List');
    }

    public function test_view_my_logging_failed_because_not_login(): void
    {
        $response = $this->get('/logging/9a3c3d3e-40ac-43f6-95d3-31f2e3687c7c');
        $response->assertStatus(302);
        $response->assertRedirect('/login');
        $this->assertTrue(session()->has('error'));
        $this->assertEquals('You must log in first!', session('error'));
    }

    public function test_view_my_logging_failed_because_uuid_is_invalid(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $this->logging->generateConnectionLog($user);

        $response = $this->get('/logging/uuid');
        $response->assertStatus(404);
    }

    public function test_view_my_logging_failed_because_logging_is_not_an_access_right(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $this->logging->generateConnectionLog($user);

        $client = User::clientFactory()->create();

        $response = $this->get("/logging/$client->uuid");
        $response->assertStatus(404);
    }

    public function test_view_my_logging_failed_because_logging_not_found(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $response = $this->get('/logging/38df4212-1c35-43c2-bb6b-80c47a15d673/edit');
        $response->assertStatus(404);
    }
}

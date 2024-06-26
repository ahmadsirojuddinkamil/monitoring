<?php

namespace Modules\Logging\tests\Feature\Controller\Fetch;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Modules\Logging\App\Services\LoggingService;
use Modules\User\App\Models\User;
use Modules\User\App\Services\RoleService;
use Tests\TestCase;

class ViewFetchLoggingTest extends TestCase
{
    use RefreshDatabase;

    protected $logging;

    protected $role;

    public function setUp(): void
    {
        parent::setUp();
        $this->logging = new LoggingService();
        $this->role = new RoleService();
    }

    public function test_view_fetch_log_success_displayed(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $this->logging->generateConnectionLog($user);

        $response = $this->get("/logging/$user->uuid/create");
        $response->assertStatus(200);
        $response->assertSeeText('Get Log Data');
    }

    public function test_view_fetch_log_failed_because_not_login(): void
    {
        $response = $this->get('/logging/d8bd6038-aa9d-4620-8cdc-711db03e5e77/create');
        $response->assertStatus(302);
        $response->assertRedirect('/login');
        $this->assertTrue(session()->has('error'));
        $this->assertEquals('You must log in first!', session('error'));
    }

    public function test_view_fetch_log_failed_because_connection_not_found(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $response = $this->get('/logging/d8bd6038-aa9d-4620-8cdc-711db03e5e77/create');
        $response->assertStatus(302);
        $response->assertRedirect("connection/$user->uuid");
        $this->assertTrue(session()->has('error'));
        $this->assertEquals('Dont have a connection account yet! Register immediately', session('error'));
    }

    public function test_view_fetch_log_failed_because_uuid_invalid(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $this->logging->generateConnectionLog($user);

        $response = $this->get('/logging/uuid/create');
        $response->assertStatus(404);
    }

    public function test_view_fetch_log_failed_because_user_not_found(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $this->logging->generateConnectionLog($user);

        $response = $this->get('/logging/d8bd6038-aa9d-4620-8cdc-711db03e5e77/create');
        $response->assertStatus(404);
    }

    public function test_view_fetch_log_failed_because_not_your_account(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $this->logging->generateConnectionLog($user);

        $client = $this->role->generateUser();
        $response = $this->get("/logging/$client->uuid/create");
        $response->assertStatus(404);
    }
}

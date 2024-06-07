<?php

namespace Modules\Connection\tests\Feature\Controllers;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Modules\Connection\App\Models\Connection;
use Modules\User\App\Models\User;
use Modules\User\App\Services\RoleService;
use Tests\TestCase;

class ViewOtherConnectionTest extends TestCase
{
    use RefreshDatabase;

    protected $role;

    public function setUp(): void
    {
        parent::setUp();
        $this->role = new RoleService();
    }

    public function test_view_other_connection_success_displayed(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $this->role->generateRole();
        $this->role->assignRoleAdministrator();

        $connection = Connection::factory()->create();

        $response = $this->get("/connection/list/$connection->uuid");
        $response->assertStatus(200);
        $response->assertSeeText('Endpoint');
    }

    public function test_view_other_connection_failed_because_not_login(): void
    {
        $response = $this->get('/connection/list/e0cd06af-f1b6-4429-81d8-1801db34f40e');
        $response->assertStatus(404);
    }

    public function test_view_other_connection_failed_because_not_admin(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $response = $this->get('/connection/list/9641b55e-1d4a-4a08-b3d5-9e0ae5ce067a');
        $response->assertStatus(404);
    }

    public function test_view_other_connection_failed_because_uuid_is_invalid(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $this->role->generateRole();
        $this->role->assignRoleAdministrator();

        $response = $this->get('/connection/list/uuid');
        $response->assertStatus(302);
        $response->assertRedirect('/connection/list');
        $this->assertTrue(session()->has('error'));
        $this->assertEquals('Invalid connection data!', session('error'));
    }

    public function test_view_other_connection_failed_because_connection_not_found(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $this->role->generateRole();
        $this->role->assignRoleAdministrator();

        $response = $this->get('/connection/list/af49813f-68a2-4d89-be8b-d5ece39af788');
        $response->assertStatus(302);
        $response->assertRedirect('/connection/list');
        $this->assertTrue(session()->has('error'));
        $this->assertEquals('Connection not found!', session('error'));
    }
}

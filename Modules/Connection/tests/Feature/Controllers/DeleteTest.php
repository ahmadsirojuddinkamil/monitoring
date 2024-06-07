<?php

namespace Modules\Connection\tests\Feature\Controllers;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Modules\Connection\App\Models\Connection;
use Modules\User\App\Models\User;
use Modules\User\App\Services\RoleService;
use Tests\TestCase;

class DeleteTest extends TestCase
{
    use RefreshDatabase;

    protected $role;

    public function setUp(): void
    {
        parent::setUp();
        $this->role = new RoleService();
    }

    public function test_delete_connection_success(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $connection = Connection::factory()->create();
        $connection->update([
            'user_uuid' => $user->uuid->toString(),
        ]);

        $this->role->generateRole();
        $this->role->assignRoleAdministrator();

        $response = $this->delete("/connection/list/$connection->uuid");
        $response->assertStatus(302);
        $response->assertRedirect('/connection/list');
        $this->assertTrue(session()->has('success'));
        $this->assertEquals('Success delete connection '.$connection->endpoint, session('success'));
    }

    public function test_delete_connection_failed_because_not_login(): void
    {
        $response = $this->delete('/connection/list/9a3c3d3e-40ac-43f6-95d3-31f2e3687c7c');
        $response->assertStatus(404);
    }

    public function test_delete_connection_failed_because_not_admin(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $response = $this->delete('/connection/list/9a3c3d3e-40ac-43f6-95d3-31f2e3687c7c');
        $response->assertStatus(404);
    }

    public function test_delete_connection_failed_because_uuid_is_invalid(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $connection = Connection::factory()->create();
        $connection->update([
            'user_uuid' => $user->uuid->toString(),
        ]);

        $this->role->generateRole();
        $this->role->assignRoleAdministrator();

        $response = $this->delete('/connection/list/uuid');

        $response->assertStatus(302);
        $response->assertRedirect('/connection/list');
        $this->assertTrue(session()->has('error'));
        $this->assertEquals('Invalid connection data!', session('error'));
    }

    public function test_delete_connection_failed_because_connection_not_found(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $connection = Connection::factory()->create();
        $connection->update([
            'user_uuid' => $user->uuid->toString(),
        ]);

        $this->role->generateRole();
        $this->role->assignRoleAdministrator();

        $response = $this->delete('/connection/list/2ee9bbcf-6364-4aa0-a167-c10e0a322ae4');
        $response->assertStatus(302);
        $response->assertRedirect('/connection/list');
        $this->assertTrue(session()->has('error'));
        $this->assertEquals('Connection not found!', session('error'));
    }
}

<?php

namespace Modules\Connection\tests\Feature\Controllers;

use Modules\User\App\Models\User;
use Modules\User\App\Services\RoleService;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ViewListTest extends TestCase
{
    use RefreshDatabase;

    protected $role;

    public function setUp(): void
    {
        parent::setUp();
        $this->role = new RoleService();
    }

    public function test_view_list_connection_success_displayed(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $this->role->generateRole();
        $this->role->assignRoleAdministrator();

        $response = $this->get('/connection/list');
        $response->assertStatus(200);
        $response->assertSeeText('API List');
    }

    public function test_view_list_connection_failed_because_not_login(): void
    {
        $response = $this->get('/connection/list');
        $response->assertStatus(404);
    }

    public function test_view_list_connection_failed_because_not_admin(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $response = $this->get('/connection/list');
        $response->assertStatus(404);
    }
}

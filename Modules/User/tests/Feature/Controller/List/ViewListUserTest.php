<?php

namespace Modules\User\Tests\Feature\Controller\List;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Modules\User\App\Services\RoleService;
use Modules\User\App\Models\User;
use Tests\TestCase;

class ViewListUserTest extends TestCase
{
    use RefreshDatabase;

    protected $role;

    public function setUp(): void
    {
        parent::setUp();
        $this->role = new RoleService();
    }

    public function test_list_user_success_displayed(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $this->role->generateRole();
        $this->role->assignRoleAdministrator();

        $response = $this->get('/user/list');
        $response->assertStatus(200);
        $response->assertViewIs('user::layouts.dashboard.user_list');
        $response->assertSeeText('User List');

        $users = $response->viewData('users');
        $this->assertNotNull($users);
    }

    public function test_list_user_failed_because_you_not_logged_in(): void
    {
        $response = $this->get('/user/list');
        $response->assertStatus(404);
    }

    public function test_list_user_failed_because_not_administrator(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $this->role->generateRole();
        $this->role->assignRoleVip();

        $response = $this->get('/user/list');
        $response->assertStatus(404);
    }
}

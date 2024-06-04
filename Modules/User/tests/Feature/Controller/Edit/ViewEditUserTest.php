<?php

namespace Modules\User\Tests\Feature\Controller\Edit;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Modules\User\App\Models\User;
use Modules\User\App\Services\RoleService;
use Tests\TestCase;

class ViewEditUserTest extends TestCase
{
    use RefreshDatabase;

    protected $role;

    public function setUp(): void
    {
        parent::setUp();
        $this->role = new RoleService();
    }

    public function test_view_edit_user_success_displayed(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $this->role->generateRole();
        $this->role->assignRoleAdministrator();
        $client = $this->role->generateUser();

        $response = $this->get("/user/$client->uuid/edit");
        $response->assertStatus(200);
        $response->assertViewIs('user::layouts.dashboard.edit');
        $response->assertSeeText('Edit profile');

        $user = $response->viewData('user');
        $this->assertNotNull($user);
    }

    public function test_view_edit_user_failed_because_you_not_logged_in(): void
    {
        $response = $this->get('/user/uuid/edit');
        $response->assertStatus(404);
    }

    public function test_view_edit_user_failed_because_not_administrator(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $this->role->generateRole();
        $this->role->assignRoleVip();

        $response = $this->get('/user/uuid/edit');
        $response->assertStatus(404);
    }

    public function test_view_edit_user_failed_because_parameter_is_invalid(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $this->role->generateRole();
        $this->role->assignRoleAdministrator();

        $response = $this->get('/user/uuid/edit');
        $response->assertStatus(302);
        $response->assertRedirect('/user/list');
        $this->assertTrue(session()->has('error'));
        $this->assertEquals('Invalid user data!', session('error'));
    }

    public function test_view_edit_user_failed_because_user_not_found(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $this->role->generateRole();
        $this->role->assignRoleAdministrator();

        $response = $this->get('/user/94ce46ef-a10a-4343-960f-af2829489254/edit');
        $response->assertStatus(302);
        $response->assertRedirect('/user/list');
        $this->assertTrue(session()->has('error'));
        $this->assertEquals('User not found!', session('error'));
    }

    public function test_view_edit_user_failed_because_cant_edit_yourself(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $this->role->generateRole();
        $this->role->assignRoleAdministrator();

        $response = $this->get("/user/$user->uuid/edit");
        $response->assertStatus(302);
        $response->assertRedirect('/user/list');
        $this->assertTrue(session()->has('error'));
        $this->assertEquals('Cant edit yourself!', session('error'));
    }
}

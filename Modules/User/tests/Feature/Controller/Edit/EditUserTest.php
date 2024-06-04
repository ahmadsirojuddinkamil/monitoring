<?php

namespace Modules\User\Tests\Feature\Controller\Edit;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Modules\User\App\Models\User;
use Modules\User\App\Services\RoleService;
use Tests\TestCase;

class EditUserTest extends TestCase
{
    use RefreshDatabase;

    protected $role;

    public function setUp(): void
    {
        parent::setUp();
        $this->role = new RoleService();
    }

    public function test_update_user_success(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $this->role->generateRole();
        $this->role->assignRoleAdministrator();
        $client = $this->role->generateUser();

        $response = $this->put("/user/$client->uuid", [
            'status' => 'bayar',
        ]);

        $response->assertStatus(302);
        $response->assertRedirect('/user/list');
        $this->assertTrue(session()->has('success'));
        $this->assertEquals('User success updated status!', session('success'));

        $client->hasRole('administrator');
    }

    public function test_update_user_failed_because_you_not_logged_in(): void
    {
        $response = $this->put('/user/uuid', [
            'status' => 'bayar',
        ]);

        $response->assertStatus(404);
    }

    public function test_update_user_failed_because_not_administrator(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $this->role->generateRole();
        $this->role->assignRoleVip();
        $client = $this->role->generateUser();

        $response = $this->put("/user/$client->uuid", [
            'status' => 'bayar',
        ]);

        $response->assertStatus(404);
    }

    public function test_update_user_failed_because_form_is_empty(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $this->role->generateRole();
        $this->role->assignRoleAdministrator();
        $client = $this->role->generateUser();

        $response = $this->put("/user/$client->uuid", [
            'status' => '',
        ]);

        $response->assertStatus(302);
        $this->assertTrue(session()->has('errors'));
    }

    public function test_update_user_failed_because_parameter_is_invalid(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $this->role->generateRole();
        $this->role->assignRoleAdministrator();

        $response = $this->put('/user/uuid', [
            'status' => 'bayar',
        ]);

        $response->assertStatus(302);
        $response->assertRedirect('/user/list');
        $this->assertTrue(session()->has('error'));
        $this->assertEquals('Invalid user data!', session('error'));
    }

    public function test_update_user_failed_because_user_not_found(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $this->role->generateRole();
        $this->role->assignRoleAdministrator();

        $response = $this->put('/user/7c4f0d4b-b2f2-40b1-8173-797edf88f9b2', [
            'status' => 'bayar',
        ]);

        $response->assertStatus(302);
        $response->assertRedirect('/user/list');
        $this->assertTrue(session()->has('error'));
        $this->assertEquals('User not found!', session('error'));
    }
}

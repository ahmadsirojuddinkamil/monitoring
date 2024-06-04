<?php

namespace Modules\User\Tests\Feature\Controller\Delete;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Modules\User\App\Models\User;
use Modules\User\App\Services\RoleService;
use Tests\TestCase;

class DeleteUserTest extends TestCase
{
    use RefreshDatabase;

    protected $role;

    public function setUp(): void
    {
        parent::setUp();
        $this->role = new RoleService();
    }

    public function test_delete_user_success(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $this->role->generateRole();
        $this->role->assignRoleAdministrator();
        $client = $this->role->generateUser();

        $response = $this->delete("/user/$client->uuid");

        $response->assertStatus(302);
        $response->assertRedirect('/user/list');
        $this->assertTrue(session()->has('success'));
        $this->assertEquals('User success deleted!', session('success'));
    }

    public function test_delete_user_failed_because_you_not_logged_in(): void
    {
        $response = $this->delete('/user/fb434b0b-f7b4-4f8c-a3a6-37cc939d5d1e');
        $response->assertStatus(404);
    }

    public function test_delete_user_failed_because_not_administrator(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $response = $this->delete('/user/1239f484-e79f-412d-9fef-4202e8294fca');
        $response->assertStatus(404);
    }

    public function test_delete_user_failed_because_parameter_is_invalid(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $this->role->generateRole();
        $this->role->assignRoleAdministrator();

        $response = $this->delete('/user/uuid');
        $response->assertStatus(302);
        $response->assertRedirect('/user/list');
        $this->assertTrue(session()->has('error'));
        $this->assertEquals('Invalid user data!', session('error'));
    }

    public function test_delete_user_failed_because_user_not_found(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $this->role->generateRole();
        $this->role->assignRoleAdministrator();

        $response = $this->delete('/user/53fe1a6d-4606-458d-a286-9d3093d2be72');
        $response->assertStatus(302);
        $response->assertRedirect('/user/list');
        $this->assertTrue(session()->has('error'));
        $this->assertEquals('User not found!', session('error'));
    }

    public function test_delete_user_failed_because_cant_delete_yourself(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $this->role->generateRole();
        $this->role->assignRoleAdministrator();

        $response = $this->delete("/user/$user->uuid");
        $response->assertStatus(302);
        $response->assertRedirect('/user/list');
        $this->assertTrue(session()->has('error'));
        $this->assertEquals('Cant erase yourself!', session('error'));
    }
}

<?php

namespace Modules\Comment\Tests\Feature\Controller\Delete;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Modules\Comment\App\Models\Comment;
use Modules\User\App\Models\User;
use Modules\User\App\Services\RoleService;
use Tests\TestCase;

class DeleteCommentTest extends TestCase
{
    use RefreshDatabase;

    protected $role;

    public function setUp(): void
    {
        parent::setUp();
        $this->role = new RoleService();
    }

    public function test_delete_comment_success(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $this->role->generateRole();
        $this->role->assignRoleAdministrator();

        $comment = Comment::factory()->create();
        $response = $this->delete("/comment/$comment->uuid");

        $response->assertStatus(302);
        $response->assertRedirect('/comment/list');
        $this->assertTrue(session()->has('success'));
        $this->assertEquals('Comment success deleted!', session('success'));
    }

    public function test_delete_comment_failed_because_you_not_logged_in(): void
    {
        $response = $this->delete('/comment/fb434b0b-f7b4-4f8c-a3a6-37cc939d5d1e');
        $response->assertStatus(404);
    }

    public function test_delete_comment_failed_because_not_administrator(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $response = $this->delete('/comment/1239f484-e79f-412d-9fef-4202e8294fca');
        $response->assertStatus(404);
    }

    public function test_delete_comment_failed_because_parameter_is_invalid(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $this->role->generateRole();
        $this->role->assignRoleAdministrator();

        $response = $this->delete('/comment/uuid');
        $response->assertStatus(302);
        $response->assertRedirect('/comment/list');
        $this->assertTrue(session()->has('error'));
        $this->assertEquals('Invalid comment data!', session('error'));
    }

    public function test_delete_comment_failed_because_comment_not_found(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $this->role->generateRole();
        $this->role->assignRoleAdministrator();

        $response = $this->delete('/comment/53fe1a6d-4606-458d-a286-9d3093d2be72');
        $response->assertStatus(302);
        $response->assertRedirect('/comment/list');
        $this->assertTrue(session()->has('error'));
        $this->assertEquals('Comment not found!', session('error'));
    }
}

<?php

namespace Modules\Comment\Tests\Feature\Controller\Edit;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Modules\Comment\App\Models\Comment;
use Modules\User\App\Models\User;
use Modules\User\App\Services\RoleService;
use Tests\TestCase;

class EditCommentTest extends TestCase
{
    use RefreshDatabase;

    protected $role;

    public function setUp(): void
    {
        parent::setUp();
        $this->role = new RoleService();
    }

    public function test_update_comment_success(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $this->role->generateRole();
        $this->role->assignRoleAdministrator();

        $comment = Comment::factory()->create();
        $response = $this->put("/comment/$comment->uuid", [
            'comment' => 'test comment update',
        ]);

        $response->assertStatus(302);
        $response->assertRedirect('/comment/list');
        $this->assertTrue(session()->has('success'));
        $this->assertEquals('Comment success updated status!', session('success'));
    }

    public function test_update_comment_failed_because_you_not_logged_in(): void
    {
        $response = $this->put('/comment/uuid', [
            'comment' => 'test comment update',
        ]);

        $response->assertStatus(404);
    }

    public function test_update_comment_failed_because_not_administrator(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $this->role->generateRole();
        $this->role->assignRoleVip();

        $comment = Comment::factory()->create();
        $response = $this->put("/comment/$comment->uuid", [
            'comment' => 'test comment update',
        ]);

        $response->assertStatus(404);
    }

    public function test_update_comment_failed_because_form_is_empty(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $this->role->generateRole();
        $this->role->assignRoleAdministrator();

        $comment = Comment::factory()->create();
        $response = $this->put("/comment/$comment->uuid", [
            'comment' => '',
        ]);

        $response->assertStatus(302);
        $this->assertTrue(session()->has('errors'));
    }

    public function test_update_comment_failed_because_parameter_is_invalid(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $this->role->generateRole();
        $this->role->assignRoleAdministrator();

        $response = $this->put('/comment/uuid', [
            'comment' => 'test comment update',
        ]);

        $response->assertStatus(302);
        $response->assertRedirect('/comment/list');
        $this->assertTrue(session()->has('error'));
        $this->assertEquals('Invalid comment data!', session('error'));
    }

    public function test_update_comment_failed_because_comment_not_found(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $this->role->generateRole();
        $this->role->assignRoleAdministrator();

        $response = $this->put('/comment/7c4f0d4b-b2f2-40b1-8173-797edf88f9b2', [
            'comment' => 'test comment update',
        ]);

        $response->assertStatus(302);
        $response->assertRedirect('/comment/list');
        $this->assertTrue(session()->has('error'));
        $this->assertEquals('Comment not found!', session('error'));
    }
}

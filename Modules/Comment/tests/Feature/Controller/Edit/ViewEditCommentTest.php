<?php

namespace Modules\Comment\Tests\Feature\Controller\Edit;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Modules\User\App\Services\RoleService;
use Modules\Comment\App\Models\Comment;
use Modules\User\App\Models\User;
use Tests\TestCase;

class ViewEditCommentTest extends TestCase
{
    use RefreshDatabase;

    protected $role;

    public function setUp(): void
    {
        parent::setUp();
        $this->role = new RoleService();
    }

    public function test_view_edit_comment_success_displayed(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $this->role->generateRole();
        $this->role->assignRoleAdministrator();

        $comment = Comment::factory()->create();
        $response = $this->get("/comment/$comment->uuid/edit");
        $response->assertStatus(200);
        $response->assertViewIs('comment::layouts.edit');
        $response->assertSeeText('Edit comment');

        $comment = $response->viewData('comment');
        $this->assertNotNull($comment);
    }

    public function test_view_edit_comment_failed_because_you_not_logged_in(): void
    {
        $response = $this->get("/comment/uuid/edit");
        $response->assertStatus(404);
    }

    public function test_view_edit_comment_failed_because_not_administrator(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $this->role->generateRole();
        $this->role->assignRoleVip();

        $response = $this->get('/comment/aee052e3-21a2-4113-a406-2abb81a2a245/edit');
        $response->assertStatus(404);
    }

    public function test_view_edit_comment_failed_because_parameter_is_invalid(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $this->role->generateRole();
        $this->role->assignRoleAdministrator();

        $response = $this->get('/comment/uuid/edit');
        $response->assertStatus(302);
        $response->assertRedirect('/comment/list');
        $this->assertTrue(session()->has('error'));
        $this->assertEquals('Invalid comment data!', session('error'));
    }

    public function test_view_edit_comment_failed_because_comment_not_found(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $this->role->generateRole();
        $this->role->assignRoleAdministrator();

        $response = $this->get('/comment/94ce46ef-a10a-4343-960f-af2829489254/edit');
        $response->assertStatus(302);
        $response->assertRedirect('/comment/list');
        $this->assertTrue(session()->has('error'));
        $this->assertEquals('Comment not found!', session('error'));
    }
}

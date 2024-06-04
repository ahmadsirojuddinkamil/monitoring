<?php

namespace Modules\Comment\Tests\Feature\Controller\List;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Modules\User\App\Services\RoleService;
use Modules\Comment\App\Models\Comment;
use Modules\User\App\Models\User;
use Tests\TestCase;

class ViewListCommentTest extends TestCase
{
    use RefreshDatabase;

    protected $role;

    public function setUp(): void
    {
        parent::setUp();
        $this->role = new RoleService();
    }

    public function test_list_comment_success_displayed(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $this->role->generateRole();
        $this->role->assignRoleAdministrator();

        Comment::factory()->create();
        $response = $this->get('/comment/list');
        $response->assertStatus(200);
        $response->assertViewIs('comment::layouts.list');
        $response->assertSeeText('Comment List');

        $comments = $response->viewData('comments');
        $this->assertNotNull($comments);
    }

    public function test_list_comment_failed_because_you_not_logged_in(): void
    {
        $response = $this->get('/comment/list');
        $response->assertStatus(404);
    }

    public function test_list_comment_failed_because_not_administrator(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $this->role->generateRole();
        $this->role->assignRoleVip();

        $response = $this->get('/comment/list');
        $response->assertStatus(404);
    }
}

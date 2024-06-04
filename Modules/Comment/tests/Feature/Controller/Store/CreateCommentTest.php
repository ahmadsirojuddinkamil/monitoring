<?php

namespace Modules\Comment\Tests\Feature\Controller\Store;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Auth;
use Modules\User\App\Models\User;
use Tests\TestCase;

class CreateCommentTest extends TestCase
{
    use RefreshDatabase;

    public function test_create_comment_success(): void
    {
        $user = User::factory()->create();
        Auth::login($user);

        $response = $this->post('/comment', [
            'comment' => 'comment test'
        ]);
        $response->assertStatus(302);
        $response->assertRedirect('/#comment');
        $this->assertTrue(session()->has('success'));
        $this->assertEquals('Success create comment, Thank you!', session('success'));
    }

    public function test_create_comment_failed_because_form_is_empty(): void
    {
        $user = User::factory()->create();
        Auth::login($user);

        $response = $this->post('/comment', [
            'comment' => ''
        ]);

        $response->assertStatus(302);
        $this->assertTrue(session()->has('errors'));
    }

    public function test_create_comment_failed_because_you_not_logged_in(): void
    {
        $response = $this->post('/comment', [
            'comment' => 'comment test'
        ]);
        $response->assertStatus(302);
        $response->assertRedirect('/login');
        $this->assertTrue(session()->has('error'));
        $this->assertEquals('You must log in first!', session('error'));
    }
}

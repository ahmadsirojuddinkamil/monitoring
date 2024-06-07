<?php

namespace Modules\Connection\tests\Feature\Controllers;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Modules\Connection\App\Models\Connection;
use Modules\User\App\Models\User;
use Tests\TestCase;

class ViewCreateTest extends TestCase
{
    use RefreshDatabase;

    public function test_view_create_connection_success_displayed(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $response = $this->get('/connection/create');
        $response->assertStatus(200);
        $response->assertSeeText('Create connection');
    }

    public function test_view_create_connection_failed_because_not_login(): void
    {
        $response = $this->get('/connection/create');
        $response->assertStatus(302);
        $response->assertRedirect('/login');
        $this->assertTrue(session()->has('error'));
        $this->assertEquals('You must log in first!', session('error'));
    }

    public function test_view_create_connection_failed_because_already_has_a_connection(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $connection = Connection::factory()->create();
        $connection->update([
            'user_uuid' => $user->uuid,
        ]);

        $response = $this->get('/connection/create');
        $response->assertStatus(404);
    }
}

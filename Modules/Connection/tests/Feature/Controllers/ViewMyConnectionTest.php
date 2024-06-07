<?php

namespace Modules\Connection\tests\Feature\Controllers;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Modules\User\App\Models\User;
use Tests\TestCase;

class ViewMyConnectionTest extends TestCase
{
    use RefreshDatabase;

    public function test_view_my_connection_success_displayed(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $response = $this->get("/connection/$user->uuid");
        $response->assertStatus(200);
        $response->assertSeeText('Connection '.$user->username);
    }

    public function test_view_my_connection_failed_because_not_login(): void
    {
        $response = $this->get('/connection/fbc0e78e-a806-4455-a8db-82e617f94ae3');
        $response->assertStatus(302);
        $response->assertRedirect('/login');
        $this->assertTrue(session()->has('error'));
        $this->assertEquals('You must log in first!', session('error'));
    }

    public function test_view_my_connection_failed_because_uuid_is_invalid(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $response = $this->get('/connection/uuid');
        $response->assertStatus(302);
        $response->assertRedirect('/connection/'.$user->uuid);
        $this->assertTrue(session()->has('error'));
        $this->assertEquals('Invalid connection data!', session('error'));
    }

    public function test_view_my_connection_failed_because_connection_not_found(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $response = $this->get('/connection/9aec2303-e9fa-42d1-be12-ade599a19f65');
        $response->assertStatus(404);
    }

    public function test_view_my_connection_failed_because_connection_is_not_an_access_right(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $client = User::clientFactory()->create();

        $response = $this->get("/connection/$client->uuid");
        $response->assertStatus(404);
    }
}

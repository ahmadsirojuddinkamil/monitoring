<?php

namespace Modules\Connection\tests\Feature\Controllers;

use Modules\Connection\App\Models\Connection;
use Modules\User\App\Models\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ViewEditTest extends TestCase
{
    use RefreshDatabase;

    public function test_view_edit_connection_success_displayed(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $connection = Connection::factory()->create();
        $connection->update([
            'user_uuid' => $user->uuid->toString(),
        ]);

        $response = $this->get("/connection/$user->uuid/edit");
        $response->assertStatus(200);
        $response->assertSeeText('Edit connection');
    }

    public function test_view_edit_connection_failed_because_not_login(): void
    {
        $response = $this->get("/connection/fbc0e78e-a806-4455-a8db-82e617f94ae3/edit");
        $response->assertStatus(302);
        $response->assertRedirect('/login');
        $this->assertTrue(session()->has('error'));
        $this->assertEquals('You must log in first!', session('error'));
    }

    public function test_view_edit_connection_failed_because_it_not_have_a_connection(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $response = $this->get("/connection/fbc0e78e-a806-4455-a8db-82e617f94ae3/edit");
        $response->assertStatus(404);
    }

    public function test_view_edit_connection_failed_because_uuid_is_invalid(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $connection = Connection::factory()->create();
        $connection->update([
            'user_uuid' => $user->uuid->toString(),
        ]);

        $response = $this->get("/connection/uuid/edit");
        $response->assertStatus(302);
        $response->assertRedirect('/connection/' . $user->uuid);
        $this->assertTrue(session()->has('error'));
        $this->assertEquals('Invalid connection data!', session('error'));
    }

    public function test_view_edit_connection_failed_because_connection_not_found(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $connection = Connection::factory()->create();
        $connection->update([
            'user_uuid' => $user->uuid->toString(),
        ]);

        $response = $this->get("/connection/38df4212-1c35-43c2-bb6b-80c47a15d673/edit");
        $response->assertStatus(404);
    }

    public function test_view_edit_connection_failed_because_connection_is_not_an_access_right(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $connection = Connection::factory()->create();
        $connection->update([
            'user_uuid' => $user->uuid->toString(),
        ]);

        $client = User::clientFactory()->create();

        $response = $this->get("/connection/$client->uuid/edit");
        $response->assertStatus(404);
    }
}

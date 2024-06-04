<?php

namespace Modules\User\Tests\Feature\Profile;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Modules\User\App\Services\RoleService;
use Modules\User\App\Models\User;
use Tests\TestCase;

class EditProfileTest extends TestCase
{
    use RefreshDatabase;

    protected $role;

    public function setUp(): void
    {
        parent::setUp();
        $this->role = new RoleService();
    }

    public function test_edit_profile_success(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $response = $this->put("/profile/$user->uuid", [
            'username' => 'client',
            'email' => $user->email,
            'old_password' => '12345678',
            'new_password' => 'client_password',
            'old_profile' => 'profile',
            'new_profile' => '',
        ]);

        $response->assertStatus(302);
        $response->assertRedirect("/profile/{$user->uuid}");
        $this->assertTrue(session()->has('success'));
        $this->assertEquals('Success update your profile!', session('success'));
    }

    public function test_edit_profile_failed_because_you_not_logged_in(): void
    {
        $response = $this->put("/profile/470d20af-a47f-4ce5-90a7-fdac0d8505f4", [
            'username' => 'client',
            'email' => 'client@gmail.com',
            'old_password' => '12345678',
            'new_password' => 'client_password',
            'old_profile' => 'profile',
            'new_profile' => '',
        ]);
        $response->assertStatus(302);
        $response->assertRedirect('/login');
        $this->assertTrue(session()->has('error'));
        $this->assertEquals('You must log in first!', session('error'));
    }

    public function test_edit_profile_failed_because_form_is_empty(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $response = $this->put("/profile/$user->uuid", [
            'username' => '',
            'email' => '',
            'old_password' => '',
            'new_password' => '',
            'old_profile' => '',
            'new_profile' => '',
        ]);

        $response->assertStatus(302);
        $this->assertTrue(session()->has('errors'));
    }

    public function test_edit_profile_failed_because_parameter_is_invalid(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $response = $this->put("/profile/uuid", [
            'username' => 'client',
            'email' => 'client@gmail.com',
            'old_password' => '12345678',
            'new_password' => 'client_password',
            'old_profile' => 'profile',
            'new_profile' => '',
        ]);
        $response->assertStatus(302);
        $response->assertRedirect('/dashboard');
        $this->assertTrue(session()->has('error'));
        $this->assertEquals('Invalid profile data!', session('error'));
    }

    public function test_edit_profile_failed_because_profile_not_found(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $response = $this->put("/profile/470d20af-a47f-4ce5-90a7-fdac0d8505f4", [
            'username' => 'client',
            'email' => 'client@gmail.com',
            'old_password' => '12345678',
            'new_password' => 'client_password',
            'old_profile' => 'profile',
            'new_profile' => '',
        ]);
        $response->assertStatus(302);
        $response->assertRedirect('/dashboard');
        $this->assertTrue(session()->has('error'));
        $this->assertEquals('User not found!', session('error'));
    }

    public function test_edit_profile_failed_because_not_your_account(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $client = $this->role->generateUser();
        $response = $this->put("/profile/$client->uuid", [
            'username' => 'client',
            'email' => $user->email,
            'old_password' => '12345678',
            'new_password' => 'client_password',
            'old_profile' => 'profile',
            'new_profile' => '',
        ]);

        $response->assertStatus(302);
        $response->assertRedirect("/dashboard");
        $this->assertTrue(session()->has('error'));
        $this->assertEquals('Invalid profile data!', session('error'));
    }

    public function test_edit_profile_failed_because_wrong_password(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $response = $this->put("/profile/$user->uuid", [
            'username' => 'client',
            'email' => $user->email,
            'old_password' => 'old_password',
            'new_password' => 'client_password',
            'old_profile' => 'profile',
            'new_profile' => '',
        ]);

        $response->assertStatus(302);
        $response->assertRedirect("/profile/{$user->uuid}");
        $this->assertTrue(session()->has('error_password'));
        $this->assertEquals('Wrong password!', session('error_password'));
    }
}

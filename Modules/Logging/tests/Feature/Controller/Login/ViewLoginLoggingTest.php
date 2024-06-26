<?php

namespace Modules\Logging\tests\Feature\Controller\Login;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Modules\Logging\App\Services\LoggingService;
use Modules\User\App\Models\User;
use Tests\TestCase;

class ViewLoginLoggingTest extends TestCase
{
    use RefreshDatabase;

    protected $logging;

    public function setUp(): void
    {
        parent::setUp();
        $this->logging = new LoggingService();
    }

    public function test_view_login_account_log_success_displayed(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $this->logging->generateConnectionLog($user);

        $response = $this->get('/logging/login');
        $response->assertStatus(200);
        $response->assertSeeText('Login immediately to get tokens!');
    }

    public function test_view_login_account_log_failed_because_not_login(): void
    {
        $response = $this->get('/logging/login');
        $response->assertStatus(302);
        $response->assertRedirect('/login');
        $this->assertTrue(session()->has('error'));
        $this->assertEquals('You must log in first!', session('error'));
    }

    public function test_view_login_account_log_failed_because_connection_not_found(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $response = $this->get('/logging/login');
        $response->assertStatus(302);
        $response->assertRedirect("connection/$user->uuid");
        $this->assertTrue(session()->has('error'));
        $this->assertEquals('Dont have a connection account yet! Register immediately', session('error'));
    }
}

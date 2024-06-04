<?php

namespace Modules\Secret\Tests\Feature\Controller\Key;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Auth;
use Modules\User\App\Models\User;
use Tests\TestCase;

class ViewSecretGeneratorTest extends TestCase
{
    use RefreshDatabase;

    public function test_view_secret_generator_success(): void
    {
        $user = User::factory()->create();
        Auth::login($user);

        $response = $this->get('/secret-generator');
        $response->assertStatus(200);
        $response->assertViewIs('secret::layouts.generator');
        $response->assertSeeText('Secret Generator');
        $response->assertViewHas('secret', null);
    }

    public function test_view_secret_generator_failed_because_you_not_logged_in(): void
    {
        $response = $this->get('/secret-generator');
        $response->assertStatus(302);
        $response->assertRedirect('/login');
        $this->assertTrue(session()->has('error'));
        $this->assertEquals('You must log in first!', session('error'));
    }
}

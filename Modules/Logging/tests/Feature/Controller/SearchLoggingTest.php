<?php

namespace Modules\Logging\tests\Feature\Controller;

use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Modules\Logging\App\Services\LoggingService;
use Modules\User\App\Models\User;
use Tests\TestCase;

class SearchLoggingTest extends TestCase
{
    use RefreshDatabase;

    protected $logging;

    public function setUp(): void
    {
        parent::setUp();
        $this->logging = new LoggingService();
    }

    public function test_search_logging_success(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $this->logging->generateConnectionLog($user);

        $timeStart = Carbon::now()->format('Y-m-d\TH:i:s');
        $timeEnd = Carbon::now()->addMonth()->format('Y-m-d\TH:i:s');

        $response = $this->get("/logging/{$user->uuid}/search?type=testing&time-start={$timeStart}&time-end={$timeEnd}");

        $response->assertStatus(200);
        $response->assertSeeText('Manage your Logging');
    }

    public function test_search_logging_failed_because_not_login(): void
    {
        $timeStart = Carbon::now()->format('Y-m-d\TH:i:s');
        $timeEnd = Carbon::now()->addMonth()->format('Y-m-d\TH:i:s');

        $response = $this->get("/logging/3e07bcff-1bf3-4600-abc1-ed08ed79ecc3/search?type=testing&time-start={$timeStart}&time-end={$timeEnd}");
        $response->assertStatus(302);
        $response->assertRedirect('/login');
        $this->assertTrue(session()->has('error'));
        $this->assertEquals('You must log in first!', session('error'));
    }

    public function test_search_logging_failed_because_uuid_is_invalid(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $this->logging->generateConnectionLog($user);

        $timeStart = Carbon::now()->format('Y-m-d\TH:i:s');
        $timeEnd = Carbon::now()->addMonth()->format('Y-m-d\TH:i:s');

        $response = $this->get("/logging/uuid/search?type=testing&time-start={$timeStart}&time-end={$timeEnd}");

        $response->assertStatus(404);
    }

    public function test_search_logging_failed_because_logging_not_found(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $this->logging->generateConnectionLog($user);

        $timeStart = Carbon::now()->format('Y-m-d\TH:i:s');
        $timeEnd = Carbon::now()->addMonth()->format('Y-m-d\TH:i:s');

        $response = $this->get("/logging/38df4212-1c35-43c2-bb6b-80c47a15d673/search?type=testing&time-start={$timeStart}&time-end={$timeEnd}");

        $response->assertStatus(404);
    }

    public function test_search_logging_failed_because_logging_is_not_an_access_right(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $this->logging->generateConnectionLog($user);

        $client = User::clientFactory()->create();

        $timeStart = Carbon::now()->format('Y-m-d\TH:i:s');
        $timeEnd = Carbon::now()->addMonth()->format('Y-m-d\TH:i:s');

        $response = $this->get("/logging/$client->uuid/search?type=testing&time-start={$timeStart}&time-end={$timeEnd}");

        $response->assertStatus(404);
    }

    public function test_search_logging_failed_because_form_is_empty(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $this->logging->generateConnectionLog($user);

        $response = $this->get("/logging/$user->uuid/search");

        $response->assertStatus(302);
        $response->assertRedirect('/logging/'.$user->uuid);
        $this->assertTrue(session()->has('error'));
    }

    public function test_search_logging_failed_because_form_is_invalid(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $this->logging->generateConnectionLog($user);

        $response = $this->get("/logging/{$user->uuid}/search?type=log&time-start=time-start&time-end=time-end");

        $response->assertStatus(302);
        $response->assertRedirect('/logging/'.$user->uuid);
        $this->assertTrue(session()->has('error'));
    }
}

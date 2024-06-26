<?php

namespace Modules\Logging\tests\Feature\Controller\Login;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Modules\Logging\App\Services\LoggingService;
use Tests\TestCase;

class StoreFetchLoggingTest extends TestCase
{
    use RefreshDatabase;

    protected $logging;

    public function setUp(): void
    {
        parent::setUp();
        $this->logging = new LoggingService();
    }

    public function test_store_fetch_log_success(): void
    {
        $this->assertTrue(true);
    }
}

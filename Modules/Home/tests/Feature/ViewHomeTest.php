<?php

namespace Modules\Home\Tests\Feature;

use Tests\TestCase;

class ViewHomeTest extends TestCase
{
    public function test_view_home_success_displayed(): void
    {
        $response = $this->get('/');
        $response->assertStatus(200);
        $response->assertViewIs('home::layouts.index');
        $response->assertSeeText('Loggingpedia');
    }
}

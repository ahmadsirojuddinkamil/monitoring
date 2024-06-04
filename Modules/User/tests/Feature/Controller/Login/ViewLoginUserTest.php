<?php

namespace Modules\User\Tests\Feature\Controller\Login;

use Tests\TestCase;

class ViewLoginUserTest extends TestCase
{
    public function test_view_login_success_displayed(): void
    {
        $response = $this->get('/login');
        $response->assertStatus(200);
        $response->assertViewIs('user::layouts.login');
        $response->assertSeeText('Login');
    }
}

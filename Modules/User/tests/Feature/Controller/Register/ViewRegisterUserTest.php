<?php

namespace Modules\User\Tests\Feature\Controller\Register;

use Tests\TestCase;

class ViewRegisterUserTest extends TestCase
{
    public function test_view_register_success_displayed(): void
    {
        $response = $this->get('/register');
        $response->assertStatus(200);
        $response->assertViewIs('user::layouts.register');
        $response->assertSeeText('Register');
    }
}

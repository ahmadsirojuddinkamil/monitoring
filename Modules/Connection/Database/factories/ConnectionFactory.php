<?php

namespace Modules\Connection\Database\factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Ramsey\Uuid\Uuid;

class ConnectionFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     */
    protected $model = \Modules\Connection\App\Models\Connection::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'uuid' => Uuid::uuid4(),
            'user_uuid' => null,
            'endpoint' => 'https://endpoint.com/',
            'register' => 'https://endpoint.com/api/register-monitoring/3737338dc931bb48bd377d72ca76444984d7234b0ea386ffa583752c9a10b236',
            'login' => 'https://endpoint.com/api/login-monitoring/0774030e118125b11bfdffe2ae51029cbfa8b243b5073f3379c356716c12fac7',
            'get_log' => 'https://endpoint.com/api/logging/9b68f7dbc448fe1aeff1a48635e4ded8bd232d887908c1b7283093cd40287d69',
            'get_log_by_type' => 'https://endpoint.com/api/logging/30ba2ed1133828e02aa01e59821dbef205ab5ea9c06cb9d097bdfd3ac34533c3/type',
            'get_log_by_time' => 'https://endpoint.com/api/logging/082534e1184185f7cd56287a6d6b0864d0274c4d33d5629ebb21a064987977d3/type/time',
            'delete_log' => 'https://endpoint.com/api/logging/0276ca0ac9d0e8442b5640072eba90f23d4c20054dc02429c9cbed60a98660b0',
            'delete_log_by_type' => 'https://endpoint.com/api/logging/e5906be35c1a3ffbe05bd706b03f6ef7685e8e6be47b1f59d3b480303602cad6/type',
            'delete_log_by_time' => 'https://endpoint.com/api/logging/05bd300fdc5781213f29f05a6f99c7b74f2eb4a5b3dfc509c9387742de180d36/type/time',
            'token' => 'b8fa4fac51c483949300cec9eb6a3b3eea14942b0c09b919fc258a7a94049515',
        ];
    }
}

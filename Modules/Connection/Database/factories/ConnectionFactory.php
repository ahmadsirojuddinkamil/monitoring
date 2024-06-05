<?php

namespace Modules\Connection\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Ramsey\Uuid\Uuid;

class ConnectionFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     */
    protected $model = \Modules\Connection\Models\Connection::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'uuid' => Uuid::uuid4(),
            'user_uuid' => null,
            'endpoint' => 'https://endpoint.com/',
            'register' => 'https://endpoint.com/register',
            'login' => 'https://endpoint.com/login',
            'get_log' => 'https://endpoint.com/get_log',
            'get_log_by_type' => 'https://endpoint.com/get_log_by_type',
            'get_log_by_time' => 'https://endpoint.com/get_log_by_time',
            'delete_log' => 'https://endpoint.com/delete_log',
            'delete_log_by_type' => 'https://endpoint.com/delete_log_by_type',
            'delete_log_by_time' => 'https://endpoint.com/delete_log_by_time',
        ];
    }
}

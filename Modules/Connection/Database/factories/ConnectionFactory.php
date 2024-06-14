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
            'register' => 'https://endpoint.com/api/register-monitoring/KEY',
            'login' => 'https://endpoint.com/api/login-monitoring/KEY',
            'get_log' => 'https://endpoint.com/api/logging/KEY',
            'get_log_by_type' => 'https://endpoint.com/api/logging/KEY/type',
            'get_log_by_time' => 'https://endpoint.com/api/logging/KEY/type/time',
            'delete_log' => 'https://endpoint.com/api/logging/KEY',
            'delete_log_by_type' => 'https://endpoint.com/api/logging/KEY/type',
            'delete_log_by_time' => 'https://endpoint.com/api/logging/KEY/type/time',
        ];
    }
}

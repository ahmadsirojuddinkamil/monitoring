<?php

namespace Modules\Connection\Database\factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Ramsey\Uuid\Uuid;

class ClientConnectionFactory extends Factory
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
            'endpoint' => 'https://client.com/',
            'register' => 'https://client.com/register-monitoring/KEY',
            'login' => 'https://client.com/login-monitoring/KEY',
            'get_log' => 'https://client.com/logging/KEY',
            'get_log_by_type' => 'https://client.com/logging/KEY/type',
            'get_log_by_time' => 'https://client.com/logging/KEY/type/time',
            'delete_log' => 'https://client.com/logging/KEY',
            'delete_log_by_type' => 'https://client.com/logging/KEY/type',
            'delete_log_by_time' => 'https://client.com/logging/KEY/type/time',
        ];
    }
}

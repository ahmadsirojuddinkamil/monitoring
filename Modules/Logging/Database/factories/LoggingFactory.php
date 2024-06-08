<?php

namespace Modules\Logging\Database\factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Ramsey\Uuid\Uuid;

class LoggingFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     */
    protected $model = \Modules\Logging\App\Models\Logging::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'uuid' => Uuid::uuid4(),
            'connection_uuid' => null,
            'type' => 'local',
            'data' => 'file excel data',
            'emergency' => 'file excel emergency',
            'alert' => 'file excel alert',
            'critical' => 'file excel critical',
            'error' => 'file excel error',
            'warning' => 'file excel warning',
            'notice' => 'file excel notice',
            'info' => 'file excel info',
            'debug' => 'file excel debug',
        ];
    }
}

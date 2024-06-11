<?php

namespace Modules\Logging\Database\factories;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;
use Ramsey\Uuid\Uuid;
use Illuminate\Support\Arr;

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
        $types = ['local', 'testing', 'production'];
        $randomType = Arr::random($types);

        return [
            'uuid' => Uuid::uuid4(),
            'connection_uuid' => 'b232a817-7d12-48e7-8842-c7d23693ffee',
            'type' => $randomType,
            'data' => 'file excel data',
            'emergency' => 'file excel emergency',
            'alert' => 'file excel alert',
            'critical' => 'file excel critical',
            'error' => 'file excel error',
            'warning' => 'file excel warning',
            'notice' => 'file excel notice',
            'info' => 'file excel info',
            'debug' => 'file excel debug',
            'created_at' => Carbon::createFromTimestamp(mt_rand(strtotime('2024-01-01'), strtotime('2024-3-31'))),
        ];
    }
}

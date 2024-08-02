<?php

namespace Modules\Logging\Database\factories;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Arr;
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
        $listEnv = ['local', 'testing', 'production'];
        $randomEnv = Arr::random($listEnv);

        $listLog = ['get_log', 'get_log_by_type', 'get_log_by_time', 'delete_log', 'delete_log_by_type', 'delete_log_by_time'];
        $randomLog = Arr::random($listLog);

        return [
            'uuid' => Uuid::uuid4(),
            'connection_uuid' => null,
            'type_env' => $randomEnv,
            'type_log' => $randomLog,
            'other' => '',
            'emergency' => '',
            'alert' => '',
            'critical' => '',
            'error' => 'public/get_log/466e8813-f629-4c63-a066-5d21fe789c2e/testing/testing_error_7dc245b7-89b2-4b06-bdc6-4719acfe8432.xlsx',
            'warning' => '',
            'notice' => '',
            'info' => '',
            'debug' => '',
            'created_at' => Carbon::createFromTimestamp(mt_rand(strtotime('2024-01-01'), strtotime('2024-3-31'))),
        ];
    }
}

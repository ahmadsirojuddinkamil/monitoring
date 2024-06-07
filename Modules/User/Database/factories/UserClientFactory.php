<?php

namespace Modules\User\Database\factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Ramsey\Uuid\Uuid;

class UserClientFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     */
    protected $model = \Modules\User\App\Models\User::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        $salt = env('SALT_USER');
        $hashPassword = password_hash($salt. 12345678 .$salt, PASSWORD_ARGON2I);

        return [
            'uuid' => Uuid::uuid4(),
            'username' => 'client',
            'email' => 'client@gmail.com',
            'password' => $hashPassword,
        ];
    }
}

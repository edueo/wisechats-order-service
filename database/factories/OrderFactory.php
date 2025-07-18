<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\User;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Order>
 */
class OrderFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
        public function definition(): array
        {
            return [
                'customer_name' => $this->faker->name(),
                'customer_email' => $this->faker->safeEmail(),
                'total' => $this->faker->randomFloat(2, 10, 1000),
                'user_id' => User::factory(),
            ];
        }
}

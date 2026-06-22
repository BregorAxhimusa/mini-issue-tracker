<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<\App\Models\Project>
 */
class ProjectFactory extends Factory
{
    public function definition(): array
    {
        $startDate = fake()->optional()->dateTimeBetween('-2 months', '+1 month');

        return [
            'user_id' => User::factory(),
            'name' => fake()->unique()->catchPhrase(),
            'description' => fake()->optional()->paragraph(),
            'start_date' => $startDate,
            'deadline' => $startDate ? fake()->dateTimeBetween($startDate, '+3 months') : null,
        ];
    }
}

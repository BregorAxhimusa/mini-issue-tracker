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
        $startDate = fake()->dateTimeBetween('-3 months', 'now');

        $earliestDeadline = max((clone $startDate)->modify('+1 month'), new \DateTime());
        $latestDeadline = (clone $startDate)->modify('+6 months');

        return [
            'user_id' => User::factory(),
            'name' => fake()->unique()->catchPhrase(),
            'description' => fake()->optional()->paragraph(),
            'start_date' => $startDate,
            'deadline' => fake()->dateTimeBetween($earliestDeadline, $latestDeadline),
        ];
    }
}

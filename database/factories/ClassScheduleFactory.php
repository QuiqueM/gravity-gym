<?php

namespace Database\Factories;

use App\Models\GymClass;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ClassSchedule>
 */
class ClassScheduleFactory extends Factory
{
    public function definition(): array
    {
        $start = Carbon::now()->addDays(fake()->numberBetween(0, 7))->setTime(fake()->numberBetween(6, 20), 0);
        $end = (clone $start)->addMinutes(fake()->randomElement([45, 60, 90]));

        return [
            'class_id' => GymClass::factory(),
            'starts_at' => $start,
            'ends_at' => $end,
            'room' => fake()->randomElement(['A', 'B', 'C', 'Sala Principal']),
        ];
    }
}



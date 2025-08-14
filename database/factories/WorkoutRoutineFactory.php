<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\WorkoutRoutine>
 */
class WorkoutRoutineFactory extends Factory
{
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'name' => fake()->randomElement(['Pecho y Tríceps', 'Espalda y Bíceps', 'Piernas', 'Full Body', 'Cardio + Core']),
            'description' => fake()->sentence(10),
        ];
    }
}



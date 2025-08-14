<?php

namespace Database\Factories;

use App\Models\WorkoutRoutine;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\WorkoutExercise>
 */
class WorkoutExerciseFactory extends Factory
{
    public function definition(): array
    {
        $hasReps = fake()->boolean(70);
        return [
            'routine_id' => WorkoutRoutine::factory(),
            'exercise' => fake()->randomElement([
                'Sentadilla', 'Press banca', 'Peso muerto', 'Dominadas', 'Remo con barra',
                'Fondos', 'Press militar', 'Curl biceps', 'Zancadas', 'Plancha'
            ]),
            'sets' => fake()->numberBetween(3, 5),
            'reps' => $hasReps ? fake()->randomElement([8, 10, 12, 15]) : null,
            'duration_seconds' => $hasReps ? null : fake()->randomElement([30, 45, 60, 90]),
            'order' => fake()->numberBetween(1, 10),
            'notes' => fake()->boolean(30) ? fake()->sentence() : null,
        ];
    }
}



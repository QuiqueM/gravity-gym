<?php

namespace Database\Factories;

use App\Models\ClassType;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\GymClass>
 */
class GymClassFactory extends Factory
{
    public function definition(): array
    {
        return [
            'title' => fake()->randomElement(['Fuerza Total', 'Cardio Intenso', 'Movilidad', 'Resistencia', 'Core Power']),
            'class_type_id' => ClassType::factory(),
            'instructor_id' => User::factory()->instructor(),
            'capacity' => fake()->numberBetween(10, 25),
            'requires_membership' => true,
        ];
    }
}



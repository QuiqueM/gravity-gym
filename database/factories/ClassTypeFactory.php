<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ClassType>
 */
class ClassTypeFactory extends Factory
{
    public function definition(): array
    {
        return [
            'name' => fake()->randomElement(['CrossFit', 'HIIT', 'Yoga', 'Pilates', 'Spinning']),
            'description' => fake()->sentence(8),
        ];
    }
}



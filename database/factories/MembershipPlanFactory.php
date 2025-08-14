<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\MembershipPlan>
 */
class MembershipPlanFactory extends Factory
{
    public function definition(): array
    {
        $name = fake()->randomElement(['Básico', 'Estándar', 'Premium']);

        return [
            'name' => $name,
            'description' => fake()->sentence(8),
            'duration_days' => 30,
            'class_limit_per_week' => $name === 'Premium' ? null : fake()->numberBetween(3, 6),
            'price' => match ($name) {
                'Básico' => 399.00,
                'Estándar' => 599.00,
                default => 799.00,
            },
            'is_active' => true,
        ];
    }
}



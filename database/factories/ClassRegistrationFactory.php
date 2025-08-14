<?php

namespace Database\Factories;

use App\Models\ClassSchedule;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ClassRegistration>
 */
class ClassRegistrationFactory extends Factory
{
    public function definition(): array
    {
        return [
            'class_schedule_id' => ClassSchedule::factory(),
            'user_id' => User::factory(),
            'status' => fake()->randomElement(['registered', 'waitlist']),
        ];
    }
}



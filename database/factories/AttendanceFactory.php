<?php

namespace Database\Factories;

use App\Models\ClassSchedule;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Attendance>
 */
class AttendanceFactory extends Factory
{
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'class_schedule_id' => ClassSchedule::factory(),
            'checked_in_at' => now(),
            'method' => fake()->randomElement(['app', 'kiosk', 'manual']),
        ];
    }
}



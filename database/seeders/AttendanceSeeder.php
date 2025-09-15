<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Attendance;
use App\Models\User;
use App\Models\ClassSchedule;

class AttendanceSeeder extends Seeder
{
    public function run(): void
    {
        $users = User::inRandomOrder()->take(20)->get();
        $schedules = ClassSchedule::inRandomOrder()->take(5)->get();

        foreach ($users as $user) {
            foreach ($schedules as $schedule) {
                Attendance::factory()->create([
                    'user_id' => $user->id,
                    'class_schedule_id' => $schedule->id,
                ]);
            }
        }
    }
}

<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ClassRegistration;
use App\Models\ClassSchedule;
use App\Models\User;

class ClassRegistrationSeeder extends Seeder
{
    public function run(): void
    {
        $user = User::whereHas('roles', function($q) { $q->where('name', 'Member'); })->first();
        $schedule = ClassSchedule::first();
        if ($user && $schedule) {
            ClassRegistration::create([
                'class_schedule_id' => $schedule->id,
                'user_id' => $user->id,
                'status' => 'active',
            ]);
        }
    }
}

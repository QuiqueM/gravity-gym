<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ClassSchedule;
use App\Models\GymClass;

class ClassScheduleSeeder extends Seeder
{
    public function run(): void
    {
        $yogaClass = GymClass::where('title', 'Yoga Matutino')->first();
        $spinningClass = GymClass::where('title', 'Spinning Nocturno')->first();

        ClassSchedule::create([
            'class_id' => $yogaClass->id,
            'starts_at' => now()->addDay()->setTime(8, 0),
            'ends_at' => now()->addDay()->setTime(9, 0),
            'room' => 'Sala 1',
        ]);
        ClassSchedule::create([
            'class_id' => $spinningClass->id,
            'starts_at' => now()->addDay()->setTime(19, 0),
            'ends_at' => now()->addDay()->setTime(20, 0),
            'room' => 'Sala 2',
        ]);
    }
}

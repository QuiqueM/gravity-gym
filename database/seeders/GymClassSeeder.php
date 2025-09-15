<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\GymClass;
use App\Models\ClassType;
use App\Models\User;

class GymClassSeeder extends Seeder
{
    public function run(): void
    {
        $instructor = User::whereHas('roles', function($q) { $q->where('name', 'Coach'); })->first();
        $yogaType = ClassType::where('name', 'Yoga')->first();
        $spinningType = ClassType::where('name', 'Spinning')->first();

        GymClass::create([
            'title' => 'Yoga Matutino',
            'class_type_id' => $yogaType->id,
            'instructor_id' => $instructor->id,
            'capacity' => 20,
            'requires_membership' => true,
        ]);
        GymClass::create([
            'title' => 'Spinning Nocturno',
            'class_type_id' => $spinningType->id,
            'instructor_id' => $instructor->id,
            'capacity' => 15,
            'requires_membership' => true,
        ]);
    }
}

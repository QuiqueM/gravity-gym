<?php

namespace App\Http\Controllers\Classes;

use App\Http\Controllers\Controller;
use App\Models\ClassSchedule;
use App\Models\ClassType;
use App\Models\GymClass;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class ClassController extends Controller
{
    public function index(): Response
    {
        return Inertia::render('classes/Index', [
            'types' => ClassType::all(),
            'classes' => GymClass::with(['type', 'instructor'])->paginate(15),
        ]);
    }

    public function schedules(GymClass $class): Response
    {
        return Inertia::render('classes/Schedules', [
            'class' => $class->load('type'),
            'schedules' => ClassSchedule::where('class_id', $class->id)->orderBy('starts_at')->get(),
        ]);
    }
}



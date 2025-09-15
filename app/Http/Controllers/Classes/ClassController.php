<?php

namespace App\Http\Controllers\Classes;

use App\Http\Controllers\Controller;
use App\Models\ClassSchedule;
use App\Models\ClassType;
use App\Models\GymClass;
use App\Models\User;
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
            'classSelected' => $class->load('type', 'instructor'),
            'schedules' => ClassSchedule::where('class_id', $class->id)->orderBy('starts_at')->get(),
        ]);
    }

    public function createType(): Response
    {
        return Inertia::render('classes/CreateTypeClass');
    }

    public function storeType(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:class_types,name',
            'description' => 'nullable|string|max:1000',
        ]);

        ClassType::create([
            'name' => $request->name,
            'description' => $request->description,
        ]);

        return to_route('classes.index')->with('success', 'Tipo de clase creado exitosamente.');
    }

    public function createClass(): Response
    {
        $coaches = User::whereHas('roles', function($q) {
            $q->where('name', 'Coach');
        })->get();
        return Inertia::render('classes/CreateClass', [
            'types' => ClassType::all(),
            'coaches' => $coaches,
        ]);
    }

    public function storeClass(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'class_type_id' => 'required|exists:class_types,id',
            'instructor_id' => 'required|exists:users,id',
            'capacity' => 'required|integer|min:1',
            'requires_membership' => 'required|boolean',
        ]);

        GymClass::create([
            'title' => $request->title,
            'class_type_id' => $request->class_type_id,
            'instructor_id' => $request->instructor_id,
            'capacity' => $request->capacity,
            'requires_membership' => $request->requires_membership,
        ]);

        return to_route('classes.index')->with('success', 'Clase creada exitosamente.');
    }

    public function createSchedule(GymClass $class): Response
    {
        return Inertia::render('classes/CreateSchedule', [
            'classSelected' => $class->load('type', 'instructor'),
        ]);
    }
}



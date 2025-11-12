<?php

namespace App\Http\Controllers\Classes;

use App\Http\Controllers\Controller;
use App\Models\ClassSchedule;
use App\Models\ClassType;
use App\Models\GymClass;
use App\Models\User;
use App\Models\ClassRegistration;
use App\Models\Membership;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;
use App\Models\Branch;

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
            'schedules' => ClassSchedule::with('branch')->where('class_id', $class->id)
                ->where('starts_at', '>=', now())
                ->orderBy('starts_at')
                ->get(),
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
            $q->where('name', 'Coach')->orWhere('name', 'Admin');
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
        $activeBranches = Branch::where('is_active', true)->get();
        return Inertia::render('classes/CreateSchedule', [
            'classSelected' => $class->load('type', 'instructor'),
            'branches' => $activeBranches,
        ]);
    }

    public function storeSchedule(Request $request, GymClass $class)
    {
        $request->validate([
            'starts_at' => 'required|date|after:now',
            'ends_at' => 'required|date|after:starts_at',
            'branch_id' => 'required|exists:branches,id',
            'repeat' => 'sometimes|boolean',
            'repeat_days' => 'sometimes|array',
            'repeat_days.*' => 'in:monday,tuesday,wednesday,thursday,friday,saturday,sunday',
            'repeat_months' => 'sometimes|integer|min:1|max:12',
        ]);

        // Crear el horario inicial
        ClassSchedule::create([
            'class_id' => $class->id,
            'starts_at' => $request->starts_at,
            'ends_at' => $request->ends_at,
            'branch_id' => $request->branch_id,
        ]);

        // Si se selecciona repetir, crear horarios adicionales
        if ($request->has('repeat') && $request->repeat) {
            $repeatDays = $request->input('repeat_days', []);
            $repeatMonths = $request->input('repeat_months', 1);
            $startDate = \Carbon\Carbon::parse($request->starts_at);
            $endDate = \Carbon\Carbon::parse($request->ends_at);

            for ($i = 0; $i <= $repeatMonths; $i++) {
                // Calcular el primer y último día del mes actual
                $monthStart = (clone $startDate)->copy()->addMonths($i)->startOfMonth();
                $monthEnd = (clone $startDate)->copy()->addMonths($i)->endOfMonth();

                foreach ($repeatDays as $day) {
                    // Buscar el primer día de la semana en el mes
                    $current = $monthStart->copy()->next($day);
                    // Si el primer día del mes ya es el día buscado, usarlo
                    if ($monthStart->englishDayOfWeek === ucfirst($day)) {
                        $current = $monthStart->copy();
                    }
                    while ($current->lte($monthEnd)) {
                        // En el primer mes, solo considerar días iguales o posteriores a la fecha original
                        if ($i === 0 && $current->lt($startDate->copy()->startOfDay())) {
                            $current->addWeek();
                            continue;
                        }
                        // Calcular la hora de inicio y fin usando la hora original
                        $start = $current->copy()->setTime($startDate->hour, $startDate->minute, $startDate->second);
                        $end = $current->copy()->setTime($endDate->hour, $endDate->minute, $endDate->second);

                        // Evitar duplicados
                        if (!ClassSchedule::where('class_id', $class->id)
                            ->where('starts_at', $start)
                            ->where('ends_at', $end)
                            ->exists()) {
                            ClassSchedule::create([
                                'class_id' => $class->id,
                                'starts_at' => $start,
                                'ends_at' => $end,
                                'branch_id' => $request->branch_id,
                            ]);
                        }
                        // Siguiente semana
                        $current->addWeek();
                    }
                }
            }
        }

        return to_route('classes.index')->with('success', 'Horario creado exitosamente.');
    }

    public function storeClassAttendance(Request $request, ClassSchedule $schedule)
    {
        $user = $request->user();

        // Verificar si el usuario ya está registrado para este horario
        if ($schedule->registrations()->where('user_id', $user->id)->exists()) {
            return back()->withErrors(['error' => 'Ya estás registrado para este horario.']);
        }

        // Verificar si la clase requiere membresía y si el usuario tiene una membresía activa
        $class = $schedule->class;
        if ($class->requires_membership) {
            $activeMembership = $user->membership()->where('is_active', true)->first();
            if (!$activeMembership) {
                return back()->withErrors(['error' => 'Esta clase requiere una membresía activa.']);
            }
        }

        // Registrar la asistencia
        ClassRegistration::create([
            'class_schedule_id' => $schedule->id,
            'user_id' => $user->id,
            'status' => 'active',
        ]);

        //reduce las clases restantes  del usuario si tiene membresía activa
        $membership = $user->activeMembership();
        if ($membership && $membership->remaining_classes > 0) {
            $membership->decrement('remaining_classes');
        }
        return back()->with('success', 'Asistencia confirmada exitosamente.');
    }

    public function editClass(GymClass $class): Response
    {
        $coaches = User::whereHas('roles', function($q) {
            $q->where('name', 'Coach')->orWhere('name', 'Admin');
        })->get();
        return Inertia::render('classes/EditClass', [
            'classSelected' => $class->load('type', 'instructor'),
            'types' => ClassType::all(),
            'coaches' => $coaches,
        ]);
    }

    public function updateClass(Request $request, GymClass $class)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'class_type_id' => 'required|exists:class_types,id',
            'instructor_id' => 'required|exists:users,id',
            'capacity' => 'required|integer|min:1',
            'requires_membership' => 'required|boolean',
        ]);

        $class->update([
            'title' => $request->title,
            'class_type_id' => $request->class_type_id,
            'instructor_id' => $request->instructor_id,
            'capacity' => $request->capacity,
            'requires_membership' => $request->requires_membership,
        ]);

        return to_route('classes.index')->with('success', 'Clase actualizada exitosamente.');
    }
}
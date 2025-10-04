<?php

namespace App\Http\Controllers\Attendance;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\ClassSchedule;
use App\Models\ClassRegistration;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class AttendanceController extends Controller
{
    public function index(Request $request): Response
    {
        $user = auth()->user();
        $date = $request->input('date') ? \Carbon\Carbon::parse($request->input('date')) : now();

        // Filtrar los horarios del día
        $schedulesQuery = ClassSchedule::with(['class', 'class.instructor'])
            ->whereDate('starts_at', $date->toDateString());

        // Si es Coach, solo ver sus clases
        if ($user->roles()->where('name', 'Coach')->exists()) {
            $schedulesQuery->whereHas('class', function($q) use ($user) {
                $q->where('instructor_id', $user->id);
            });
        }

        $schedules = $schedulesQuery->get();

        // Para cada horario, obtener sus asistencias
        $result = [];
        foreach ($schedules as $schedule) {
            $attendances = $schedule->registrations()->count();
            $result[] = [
                'schedule' => $schedule,
                'attendances' => $attendances,
            ];
        }

        return Inertia::render('attendance/Index', [
            'schedules' => $result,
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'user_id' => ['required', 'exists:users,id'],
            'class_schedule_id' => ['nullable', 'exists:class_schedules,id'],
            'method' => ['nullable', 'string'],
        ]);

        $validated['checked_in_at'] = now();
        Attendance::create($validated);

        return back()->with('success', 'Asistencia registrada');
    }

    public function show(ClassSchedule $schedule): Response
    {
        $schedule->load('class', 'class.instructor');

        // Obtener todos los registros de asistencia para la clase y horario específicos
        $attendances = ClassRegistration::where('class_schedule_id', $schedule->id)
            ->with('user')
            ->get();
        $checkAttendance = Attendance::where('class_schedule_id', $schedule->id)
            ->get()
            ->keyBy('user_id');

         // Marcar si el usuario ha asistido
         foreach ($attendances as $attendance) {
            $attendance->attended = $checkAttendance->has($attendance->user_id);
         }

        return Inertia::render('attendance/List', [
            'schedule' => $schedule,
            'attendances' => $attendances,
        ]);
    }
}



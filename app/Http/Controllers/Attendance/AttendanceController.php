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
use Illuminate\Support\Carbon;

class AttendanceController extends Controller
{
    public function index(Request $request): Response
    {
        $user = auth()->user();
        $date = $request->input('date') ? Carbon::parse($request->input('date')) : now();

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

    /**
     * Obtener las próximas asistencias del usuario, ordenadas por la más cercana a la fecha de consulta.
     */
    public function upcoming(): Response
    {
        $user = auth()->user();
        $date = now();

        // Buscar registros de inscripción futuros para el usuario
        $registrations = ClassRegistration::where('user_id', $user->id)
            ->whereHas('classSchedule', function($q) use ($date) {
                $q->where('starts_at', '>=', $date);
            })
            ->with(['classSchedule.class.instructor'])
            ->orderBy('class_schedule_id')
            ->get()
            ->sortBy(function($registration) {
                return $registration->classSchedule->starts_at ?? null;
            });

        return Inertia::render('attendance/Upcoming', [
            'registrations' => $registrations,
        ]);
    }

    public function cancelRegistration(Request $request, ClassRegistration $registration): RedirectResponse
    {
        $user = auth()->user();
        // Verificar que el registro de inscripción pertenezca al usuario autenticado
        if ($registration->user_id !== $user->id) {
            return back()->withErrors(['error' => 'No tienes permiso para cancelar esta inscripción.']);
        }
        // Verificar que la clase comience en más de una hora (ajustando zona horaria)
        $startsAt = $registration->classSchedule->starts_at->copy()->setTimezone('UTC');
        $now = now()->setTimezone('UTC');
        $diff = $now->diffInMinutes($startsAt, false); // positivo si startsAt es futuro
        if ($registration->classSchedule && $diff < 60) {
            return back()->withErrors(['error' => 'No puedes cancelar la inscripción con menos de una hora de anticipación.']);
        }

        // Cancelar la inscripción
        $registration->delete();

        //aumenta las clases disponibles del usuario si tiene membresía activa
        $membership = $user->activeMembership();
        if ($membership) {
            $membership->increment('remaining_classes');
        }

        return back()->with('success', 'Inscripción cancelada exitosamente.');
    }
}



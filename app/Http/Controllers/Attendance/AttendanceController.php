<?php

namespace App\Http\Controllers\Attendance;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class AttendanceController extends Controller
{
    public function index(): Response
    {
        return Inertia::render('attendance/Index', [
            'attendances' => Attendance::with(['user', 'schedule.class'])->latest()->paginate(20),
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
}



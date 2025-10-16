<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $roles = $user->roles->pluck('name')->toArray();
        $nextClass = null;

        if (in_array('Member', $roles)) {
            // Buscar la próxima clase registrada del usuario
            $registrations = $user->classRegistrations()
                ->whereHas('classSchedule', function($q) {
                    $q->where('starts_at', '>', now());
                })
                ->with('classSchedule.class')
                ->get();
            $registration = $registrations->sortBy(function($reg) {
                return $reg->classSchedule->starts_at ?? now()->addYears(10);
            })->first();
            $nextClass = $registration ? $registration->classSchedule : null;
        } elseif (in_array('Coach', $roles)) {
            // Buscar la próxima clase que impartirá el coach
            $nextClass = $user->instructedClasses()
                ->with(['schedules' => function($q) {
                    $q->where('starts_at', '>', now());
                }])
                ->get()
                ->pluck('schedules')
                ->flatten()
                ->sortBy('starts_at')
                ->first();
        }

        return inertia('Dashboard', [
            'nextClass' => $nextClass,
        ]);
    }
}

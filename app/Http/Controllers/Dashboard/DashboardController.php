<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Membership;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $roles = $user->roles->pluck('name')->toArray();

        if (in_array('Member', $roles)) {
            $nextClass = $this->getNextClassForMember($user);
            return inertia('Dashboard', ['nextClass' => $nextClass]);
        } elseif (in_array('Coach', $roles)) {
            $nextClass = $this->getNextClassForCoach($user);
            return inertia('Dashboard', ['nextClass' => $nextClass]);
        } elseif (in_array('Admin', $roles)) {
            $stats = $this->getAdminStats();
            return inertia('Dashboard', ['stats' => $stats]);
        }

        return inertia('Dashboard');
    }

    private function getNextClassForMember($user)
    {
        $registrations = $user->classRegistrations()
            ->whereHas('classSchedule', function($q) {
                $q->where('starts_at', '>', now());
            })
            ->with('classSchedule.class')
            ->get();

        $registration = $registrations->sortBy(function($reg) {
            return $reg->classSchedule->starts_at ?? now()->addYears(10);
        })->first();

        return $registration ? $registration->classSchedule : null;
    }

    private function getNextClassForCoach($user)
    {
        return $user->instructedClasses()
            ->with(['schedules' => function($q) {
                $q->where('starts_at', '>', now());
            }])
            ->with('schedules.class')
            ->get()
            ->pluck('schedules')
            ->flatten()
            ->sortBy('starts_at')
            ->first();
    }

    private function getAdminStats()
    {
        $totalMembers = User::whereHas('roles', function($q) {
            $q->where('name', 'Member');
        })->count();

        $currentMonthMembers = User::whereHas('roles', function($q) {
            $q->where('name', 'Member');
        })
        ->whereMonth('created_at', now()->month)
        ->whereYear('created_at', now()->year)
        ->count();

        $activeMemberships = Membership::where('is_active', true)->count();

        $expiringSoonMemberships = Membership::where('is_active', true)
            ->where('ends_at', '<=', now()->addDays(5))
            ->where('ends_at', '>=', now())
            ->count();

        return [
            'total_members' => $totalMembers,
            'current_month_members' => $currentMonthMembers,
            'active_memberships' => $activeMemberships,
            'expiring_soon_memberships' => $expiringSoonMemberships,
        ];
    }
}

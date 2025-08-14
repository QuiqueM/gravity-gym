<?php

namespace App\Http\Controllers\Memberships;

use App\Http\Controllers\Controller;
use App\Models\Membership;
use App\Models\MembershipPlan;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class MembershipController extends Controller
{
    public function index(): Response
    {
        return Inertia::render('memberships/Index', [
            'plans' => MembershipPlan::query()->where('is_active', true)->orderBy('price')->get(),
            'memberships' => Membership::with(['user', 'plan'])->latest()->paginate(15),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'user_id' => ['required', 'exists:users,id'],
            'membership_plan_id' => ['required', 'exists:membership_plans,id'],
            'starts_at' => ['required', 'date'],
        ]);

        $plan = MembershipPlan::findOrFail($validated['membership_plan_id']);
        $validated['ends_at'] = \Carbon\Carbon::parse($validated['starts_at'])->addDays($plan->duration_days);
        $validated['status'] = 'active';

        Membership::create($validated);

        return back()->with('success', 'Membresía creada');
    }
}



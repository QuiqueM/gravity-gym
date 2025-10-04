<?php

namespace App\Http\Controllers\Memberships;

use App\Http\Controllers\Controller;
use App\Models\Membership;
use App\Models\MembershipPlan;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;
use App\Http\Requests\MembershipPlanRequest;

class MembershipController extends Controller
{
    public function index(Request $request): Response
    {
        $query = Membership::query();

        if ($request->filled('filter')) {
            switch ($request->filter) {
                case 'active':
                    $query->where('status', 'active');
                    break;
                case 'expiring':
                    $query->where('status', 'active')
                        ->whereDate('ends_at', '>', now())
                        ->whereDate('ends_at', '<=', now()->addDays(5));
                    break;
                case 'expired':
                    $query->where('status', 'expired');
                    break;
            }
        }

        return Inertia::render('memberships/Index', [
            'plans' => MembershipPlan::query()->where('is_active', true)->orderBy('price')->get(),
            'memberships' => $query->with(['user', 'plan'])->latest()->paginate(15),
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

    public function createMembershipPlan(): Response
    {
        return Inertia::render('memberships/Create');
    }

    public function storeMembershipPlan(MembershipPlanRequest $request): RedirectResponse
    {
        $validated = $request->validated();
        MembershipPlan::create($validated);

        return to_route('memberships.index')->with('success', 'Plan de membresía creado');
    }

    public function edit(MembershipPlan $plan): Response
    {
        return Inertia::render('memberships/Edit', [
            'plan' => $plan,
        ]);
    }

    public function update(MembershipPlanRequest $request, MembershipPlan $plan): RedirectResponse
    {
        $validated = $request->validated();
        $plan->update($validated);

        return to_route('memberships.index')->with('success', 'Plan de membresía actualizado');
    }

    public function myMembership(): Response
    {
        $user = auth()->user();
        $membership = Membership::with('plan')->where('user_id', $user->id)->latest()->first();

        return Inertia::render('memberships/MyMembership', [
            'membership' => $membership,
        ]);
    }
}



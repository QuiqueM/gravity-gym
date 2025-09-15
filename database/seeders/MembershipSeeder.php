<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Membership;
use App\Models\MembershipPlan;
use App\Models\User;
use Carbon\Carbon;

class MembershipSeeder extends Seeder
{
    public function run(): void
    {
        $plan = MembershipPlan::where('name', 'Básico')->first();
        $users = User::whereHas('roles', function($q) { $q->where('name', 'Member'); })->take(50)->get();

        foreach ($users as $user) {
            Membership::create([
                'user_id' => $user->id,
                'membership_plan_id' => $plan->id,
                'starts_at' => Carbon::now()->subDays(rand(0, 10)),
                'ends_at' => Carbon::now()->addDays(20),
                'status' => 'active',
            ]);
        }
    }
}

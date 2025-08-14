<?php

namespace Database\Factories;

use App\Models\MembershipPlan;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Membership>
 */
class MembershipFactory extends Factory
{
    public function definition(): array
    {
        $startsAt = fake()->dateTimeBetween('-30 days', 'now');
        $endsAt = (clone $startsAt)->modify('+30 days');

        return [
            'user_id' => User::factory(),
            'membership_plan_id' => MembershipPlan::factory(),
            'starts_at' => $startsAt,
            'ends_at' => $endsAt,
            'status' => 'active',
        ];
    }
}



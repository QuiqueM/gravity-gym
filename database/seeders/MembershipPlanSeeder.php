<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\MembershipPlan;

class MembershipPlanSeeder extends Seeder
{
  public function run(): void
  {
    $plans = [
        [
            'name' => 'Conócenos',
            'description' => 'Prueba gratuita de 7 días',
            'duration_days' => 7,
            'class_limit' => 1,
            'price' => 0.00,
            'requires_payment' => false,
            'is_active' => true,
            'is_membership_initial' => true,
        ],
    ];
    foreach ($plans as $plan) {
        MembershipPlan::create($plan);
    }
  }
}

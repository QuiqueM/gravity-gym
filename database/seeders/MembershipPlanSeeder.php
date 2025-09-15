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
            'name' => 'Básico',
            'description' => 'Acceso a clases limitadas',
            'duration_days' => 30,
            'class_limit_per_week' => 3,
            'price' => 299.00,
            'requires_payment' => true,
            'is_active' => true,
        ],
        [
            'name' => 'Ilimitado',
            'description' => 'Acceso ilimitado a todas las clases',
            'duration_days' => 30,
            'class_limit_per_week' => null,
            'price' => 499.00,
            'requires_payment' => true,
            'is_active' => true,
        ],
        [
            'name' => 'Demo',
            'description' => 'Prueba gratuita de 7 días',
            'duration_days' => 7,
            'class_limit_per_week' => 2,
            'price' => 0.00,
            'requires_payment' => false,
            'is_active' => true,
        ],
    ];
    foreach ($plans as $plan) {
        MembershipPlan::create($plan);
    }
  }
}

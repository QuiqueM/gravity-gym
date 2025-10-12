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
      'features' => [
        'Acceso limitado',
        'Sin costo',
      ],
    ],
    [
      'name' => 'Basico',
      'description' => 'plan de 15 dias',
      'duration_days' => 15,
      'class_limit' => 15,
      'price' => 1,
      'requires_payment' => true,
      'is_active' => true,
      'is_membership_initial' => false,
      'features' => [
        'Entradas a 15 clases',
        'Acceso a todas las áreas',
      ],
    ],
  ];
    foreach ($plans as $plan) {
        MembershipPlan::create($plan);
    }
  }
}

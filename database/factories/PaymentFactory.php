<?php

namespace Database\Factories;

use App\Models\Membership;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Payment>
 */
class PaymentFactory extends Factory
{
    public function definition(): array
    {
        $status = fake()->randomElement(['paid', 'pending']);

        return [
            'user_id' => User::factory(),
            'membership_id' => Membership::factory(),
            'amount' => fake()->randomFloat(2, 199, 999),
            'currency' => 'MXN',
            'provider' => 'openpay',
            'provider_charge_id' => $status === 'paid' ? 'ch_' . fake()->bothify('##########') : null,
            'status' => $status,
            'meta' => [
                'method' => fake()->randomElement(['card', 'cash']),
            ],
        ];
    }
}



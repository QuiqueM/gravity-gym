<?php

namespace App\Http\Controllers\Payments;

use App\Http\Controllers\Controller;
use App\Models\Membership;
use App\Models\Payment;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class OpenPayController extends Controller
{
    public function charge(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'user_id' => ['required', 'exists:users,id'],
            'membership_id' => ['nullable', 'exists:memberships,id'],
            'amount' => ['required', 'numeric', 'min:1'],
            'currency' => ['nullable', 'string', 'size:3'],
            'token_id' => ['required', 'string'], // token proveniente de OpenPay JS
        ]);

        // Placeholder: integrar SDK de OpenPay aquí
        try {
            // Simulación de cargo exitoso para flujo inicial
            $chargeId = 'sim_' . uniqid();

            Payment::create([
                'user_id' => $validated['user_id'],
                'membership_id' => $validated['membership_id'] ?? null,
                'amount' => $validated['amount'],
                'currency' => $validated['currency'] ?? 'MXN',
                'provider' => 'openpay',
                'provider_charge_id' => $chargeId,
                'status' => 'paid',
                'meta' => [
                    'token_id' => $validated['token_id'],
                ],
            ]);

            if (!empty($validated['membership_id'])) {
                $membership = Membership::find($validated['membership_id']);
                if ($membership && $membership->status !== 'active') {
                    $membership->update(['status' => 'active']);
                }
            }

            return back()->with('success', 'Pago procesado correctamente');
        } catch (\Throwable $e) {
            Log::error('OpenPay error', ['error' => $e->getMessage()]);
            return back()->with('error', 'No se pudo procesar el pago');
        }
    }
}



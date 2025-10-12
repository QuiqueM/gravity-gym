<?php

namespace App\Http\Controllers\Payments;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;
use MercadoPago\Client\Preference\PreferenceClient;
use MercadoPago\MercadoPagoConfig;
use App\Models\Payment;
use App\Models\MembershipPlan;
use App\Models\User;

class MercadoPagoController extends \App\Http\Controllers\Controller
{
    public function createMembershipPayment(Request $request)
    {
      try {
          // Configurar credenciales
          MercadoPagoConfig::setAccessToken(config('services.mercadopago.token'));
    
          $user = auth()->user();
          $plan = $request->input('plan');
          $amount = $request->input('amount');
    
          $client = new PreferenceClient();
          $preference = $client->create([
              "items" => [
                  [
                      "title" => 'Membresía ' . ($plan['name'] ?? ''),
                      "quantity" => 1,
                      "unit_price" => (float) $amount,
                  ]
              ],
              "payer" => [
                  "email" => $user->email,
              ],
              "back_urls" => [
                  "success" => route('payments.mercadopago.success'),
                  "failure" => route('payments.mercadopago.failure'),
                  "pending" => route('payments.mercadopago.pending'),
              ],
              "notification_url" => route('payments.mercadopago.webhook'),
              "external_reference" => $user->id . '-' . ($plan['id'] ?? '') . '-' . time(), // Referencia única para tu sistema
              "metadata" => [
                  "user_id" => $user->id,
                  "membership_plan_id" => $plan['id'] ?? null,
                  "payment_type" => "membership",
              ],
              "auto_return" => "approved", // Redireccionar automáticamente después del pago aprobado
              "expires" => true,
              "expiration_date_from" => now()->toIso8601String(),
              "expiration_date_to" => now()->addHours(24)->toIso8601String(),
          ]);
          return Inertia::location($preference->init_point);
        } catch (\Exception $e) {
            Log::error('Error creando preferencia de Mercado Pago', [
                'error' => $e->getMessage(),
                'user_id' => auth()->user()->id ?? null,
                'membership_plan_id' => $plan['id'] ?? null,
                'mp_api_response' => method_exists($e, 'getApiResponse') && $e->getApiResponse() ? json_encode($e->getApiResponse()->getContent(), JSON_PRETTY_PRINT) : 'No API response content available',
            ]);

            return response()->json([
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Manejar notificaciones webhook de Mercado Pago
     */
    public function handleWebhook(Request $request)
    {
        Log::info('Mercado Pago Webhook Received', $request->all());

        if ($request->input('type') === 'payment') {
            $paymentId = $request->input('data.id');
            try {
                MercadoPagoConfig::setAccessToken(config('services.mercadopago.token'));
                $client = new \MercadoPago\Client\Payment\PaymentClient();
                $payment = $client->get($paymentId);

                if ($payment && $payment->status === 'approved') {
                    // Lógica para activar la membresía o actualizar el estado del pago
                    Log::info('Payment Approved', ['payment_id' => $paymentId]);
                    
                    // Aquí puedes usar la metadata para encontrar el usuario y el plan
                    $metadata = (array) $payment->metadata;
                    $userId = $metadata['user_id'] ?? null;
                    $membershipPlanId = $metadata['membership_plan_id'] ?? null;
                    $paymentType = $metadata['payment_type'] ?? null;

                    if ($userId && $membershipPlanId && $paymentType === 'membership') {
                        $user = User::find($userId);
                        $membershipPlan = MembershipPlan::find($membershipPlanId);

                        if ($user && $membershipPlan) {
                            // Verificar si ya tiene una membresía activa de este plan o si es una renovación
                            $existingMembership = $user->memberships()->where('membership_plan_id', $membershipPlan->id)->first();

                            if ($existingMembership) {
                                // Renovar membresía
                                $existingMembership->update([
                                    'start_date' => now(),
                                    'end_date' => now()->addDays($membershipPlan->duration_days),
                                    'is_active' => true,
                                    'remaining_classes' => ($membershipPlan->class_limit + $existingMembership->remaining_classes),
                                ]);
                                Log::info('Membership renewed for user', ['user_id' => $userId, 'plan_id' => $membershipPlanId]);
                            } else {
                                // Crear nueva membresía
                                $user->memberships()->create([
                                    'membership_plan_id' => $membershipPlan->id,
                                    'start_date' => now(),
                                    'end_date' => now()->addDays($membershipPlan->duration_days),
                                    'is_active' => true,
                                    'remaining_classes' => $membershipPlan->class_limit,
                                ]);
                                Log::info('New membership created for user', ['user_id' => $userId, 'plan_id' => $membershipPlanId]);
                            }

                            // Registrar el pago en la base de datos
                            Payment::create([
                                'user_id' => $userId,
                                'membership_plan_id' => $membershipPlanId,
                                'amount' => $payment->transaction_amount,
                                'currency' => $payment->currency_id,
                                'payment_method' => $payment->payment_method_id,
                                'status' => $payment->status,
                                'external_id' => $payment->id,
                                'external_reference' => $payment->external_reference,
                                'external_status' => $payment->status_detail,
                                'processed_at' => now(),
                                'metadata' => $metadata,
                            ]);
                            Log::info('Payment recorded in database', ['payment_id' => $paymentId]);
                        } else {
                            Log::warning('User or Membership Plan not found for payment', ['payment_id' => $paymentId, 'user_id' => $userId, 'membership_plan_id' => $membershipPlanId]);
                        }
                    } else {
                        Log::warning('Missing metadata for payment processing', ['payment_id' => $paymentId, 'metadata' => $metadata]);
                    }
                } else if ($payment && ($payment->status === 'pending' || $payment->status === 'in_process')) {
                    Log::info('Payment Pending or In Process', ['payment_id' => $paymentId]);
                    // Opcional: Actualizar el estado del pago en tu base de datos a pendiente
                    // Puedes registrar el pago aquí si lo deseas, con estado pendiente
                } else if ($payment && $payment->status === 'rejected') {
                    Log::warning('Payment Rejected', ['payment_id' => $paymentId, 'status_detail' => $payment->status_detail]);
                    // Opcional: Actualizar el estado del pago en tu base de datos a rechazado
                    // Puedes registrar el pago aquí si lo deseas, con estado rechazado
                }
            } catch (\MercadoPago\Exceptions\MPApiException $e) {
                $apiResponse = $e->getApiResponse();
                Log::error('Error processing Mercado Pago webhook from API', [
                    'payment_id' => $paymentId,
                    'error' => $e->getMessage(),
                    'api_response_status' => $apiResponse ? $apiResponse->getStatusCode() : 'N/A',
                    // 'api_response_headers' => $apiResponse ? $apiResponse->getHeaders() : 'N/A',
                    'api_response_content' => $apiResponse ? json_decode($apiResponse->getContent(), true) : 'No API response content available',
                ]);
                return response()->json(['status' => 'error', 'message' => $e->getMessage(), 'details' => $apiResponse ? $apiResponse->getContent() : 'No details'], 500);    
            }
        }

    }

    /**
     * Procesar notificación de pago
     */
    // private function processPaymentNotification($paymentId)
    // {
    //     try {
    //         // Obtener información del pago desde Mercado Pago
    //         $payment = \MercadoPago\Payment::find_by_id($paymentId);
            
    //         if (!$payment) {
    //             Log::warning('Pago no encontrado en Mercado Pago', ['payment_id' => $paymentId]);
    //             return;
    //         }
    //         // Buscar el pago en nuestra base de datos
    //         $localPayment = Payment::where('external_id', $paymentId)->first();
            
    //         if (!$localPayment) {
    //             Log::warning('Pago local no encontrado', ['payment_id' => $paymentId]);
    //             return;
    //         }
    //         // Actualizar estado del pago
    //         $status = $this->mapMercadoPagoStatus($payment->status);
    //         $localPayment->update([
    //             'status' => $status,
    //             'external_status' => $payment->status,
    //             'processed_at' => now(),
    //         ]);
    //         // Si el pago fue aprobado, activar membresía
    //         if ($status === 'approved') {
    //             $this->activateMembership($localPayment);
    //         }
    //         Log::info('Pago procesado exitosamente', [
    //             'payment_id' => $paymentId,
    //             'status' => $status,
    //             'user_id' => $localPayment->user_id,
    //         ]);
    //     } catch (\Exception $e) {
    //         Log::error('Error procesando notificación de pago', [
    //             'payment_id' => $paymentId,
    //             'error' => $e->getMessage(),
    //         ]);
    //     }
    // }

    /**
     * Mapear estados de Mercado Pago a estados locales
     */
    private function mapMercadoPagoStatus($mpStatus)
    {
        $statusMap = [
            'approved' => 'approved',
            'pending' => 'pending',
            'rejected' => 'rejected',
            'cancelled' => 'cancelled',
            'refunded' => 'refunded',
        ];

        return $statusMap[$mpStatus] ?? 'pending';
    }

    /**
     * Activar membresía del usuario
     */
    // private function activateMembership($payment)
    // {
    //     try {
    //         DB::transaction(function () use ($payment) {
    //             // Crear o actualizar membresía del usuario
    //             $membership = $payment->user->memberships()->updateOrCreate(
    //                 ['membership_plan_id' => $payment->membership_plan_id],
    //                 [
    //                     'start_date' => now(),
    //                     'end_date' => now()->addMonth(), // Asumiendo membresía mensual
    //                     'status' => 'active',
    //                     'payment_id' => $payment->id,
    //                 ]
    //             );

    //             Log::info('Membresía activada', [
    //                 'user_id' => $payment->user_id,
    //                 'membership_id' => $membership->id,
    //                 'payment_id' => $payment->id,
    //             ]);
    //         });
    //     } catch (\Exception $e) {
    //         Log::error('Error activando membresía', [
    //             'payment_id' => $payment->id,
    //             'error' => $e->getMessage(),
    //         ]);
    //     }
    // }

    /**
     * Página de éxito
     */
    public function success(Request $request)
    {
        $paymentId = $request->query('payment_id');
        $status = $request->query('status');
        $externalReference = $request->query('external_reference');

        return Inertia::render('payments/Success', [
            'paymentId' => $paymentId,
            'status' => $status,
            'externalReference' => $externalReference,
        ]);
    }

    /**
     * Página de fallo
     */
    public function failure(Request $request)
    {
        $paymentId = $request->query('payment_id');
        $status = $request->query('status');
        $externalReference = $request->query('external_reference');
        $paymentType = $request->query('payment_type');
        $preferenceId = $request->query('preference_id');

        return Inertia::render('payments/Failure', [
            'paymentId' => $paymentId,
            'status' => $status,
            'externalReference' => $externalReference,
            'paymentType' => $paymentType,
            'preferenceId' => $preferenceId,
        ]);
    }

    /**
     * Página de pendiente
     */
    public function pending(Request $request)
    {
        $paymentId = $request->query('payment_id');
        $status = $request->query('status');
        $externalReference = $request->query('external_reference');
        $paymentType = $request->query('payment_type');
        $preferenceId = $request->query('preference_id');

        return Inertia::render('payments/Pending', [
            'paymentId' => $paymentId,
            'status' => $status,
            'externalReference' => $externalReference,
            'paymentType' => $paymentType,
            'preferenceId' => $preferenceId,
        ]);
    }
}

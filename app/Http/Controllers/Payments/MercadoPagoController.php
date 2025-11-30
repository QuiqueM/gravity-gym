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
use App\Models\Membership;

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
              //"notification_url" => route('payments.mercadopago.webhook'),
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
                'error' => $e->getMessage(),
                'mp_api_response' => method_exists($e, 'getApiResponse') && $e->getApiResponse() ? $e->getApiResponse()->getContent() : 'No API response content available',
            ], 500);
        }
    }

    /**
     * Manejar notificaciones webhook de Mercado Pago
     */
    public function handleWebhook(Request $request)
    {
        if ($request->input('type') === 'payment') {
            Log::info('Mercado Pago Webhook Received', $request->all());
            $paymentId = $request->input('data.id');
            
            try {
                // 🔥 PROTECCIÓN 1: Verificar si ya procesamos este pago
                $existingPayment = Payment::where('external_id', $paymentId)->first();
                if ($existingPayment && $existingPayment->status === 'approved') {
                    Log::info('Payment already processed, skipping', ['payment_id' => $paymentId]);
                    return response()->json(['status' => 'ok', 'message' => 'Payment already processed']);
                }
                
                MercadoPagoConfig::setAccessToken(config('services.mercadopago.token'));
                $client = new \MercadoPago\Client\Payment\PaymentClient();
                $payment = $client->get($paymentId);

                // DEBUG: Verificar el formato de metadata
                Log::info('Payment metadata debug', [
                    'payment' => $payment,
                    'metadata_type' => gettype($payment->metadata),
                    'metadata_json' => json_encode($payment->metadata),
                    'payment_external_reference' => $payment->external_reference
                ]);

                if ($payment && $payment->status === 'approved') {
                    // Lógica para activar la membresía o actualizar el estado del pago
                    Log::info('Payment Approved', ['payment_id' => $paymentId]);
                    
                    // Usar external_reference como respaldo
                    $externalRef = $payment->external_reference; // formato: "user_id-plan_id-timestamp"
                    $parts = explode('-', $externalRef);
                    
                    $userId = $parts[0] ?? null;
                    $membershipPlanId = $parts[1] ?? null;
                    $paymentType = 'membership';
                    
                    // 🔥 PROTECCIÓN 2: Verificar si ya existe una membresía reciente para este usuario
                    if ($userId) {
                        $recentMembership = Membership::where('user_id', $userId)
                            ->where('is_active', true)
                            ->where('created_at', '>=', now()->utc()->subMinutes(5)) // Creada en los últimos 5 minutos
                            ->first();
                        
                        if ($recentMembership) {
                            Log::info('Recent membership already exists for user, skipping', [
                                'user_id' => $userId, 
                                'membership_id' => $recentMembership->id,
                                'payment_id' => $paymentId
                            ]);
                            return response()->json(['status' => 'ok', 'message' => 'Recent membership already exists']);
                        }
                    }
                    
                    // Verificar metadata con múltiples métodos
                    if ($payment->metadata) {
                        if (is_object($payment->metadata)) {
                            // Opción 2: Acceder como propiedades del objeto
                            $userId = $payment->metadata->user_id ?? $userId;
                            $membershipPlanId = $payment->metadata->membership_plan_id ?? $membershipPlanId;
                            $paymentType = $payment->metadata->payment_type ?? $paymentType;
                        } elseif (is_array($payment->metadata)) {
                            // Si ya es array
                            $userId = $payment->metadata['user_id'] ?? $userId;
                            $membershipPlanId = $payment->metadata['membership_plan_id'] ?? $membershipPlanId;
                            $paymentType = $payment->metadata['payment_type'] ?? $paymentType;
                        } else {
                            // Intentar convertir a array
                            $metadata = json_decode(json_encode($payment->metadata), true);
                            $userId = $metadata['user_id'] ?? $userId;
                            $membershipPlanId = $metadata['membership_plan_id'] ?? $membershipPlanId;
                            $paymentType = $metadata['payment_type'] ?? $paymentType;
                        }
                    }
                    
                    Log::info('Processing payment with extracted data', [
                        'user_id' => $userId,
                        'membership_plan_id' => $membershipPlanId,
                        'payment_type' => $paymentType,
                        'external_reference' => $externalRef
                    ]);

                    if ($userId && $membershipPlanId && $paymentType === 'membership') {
                        $user = User::find($userId);
                        $membershipPlan = MembershipPlan::find($membershipPlanId);

                        if ($user && $membershipPlan) {
                            // 🔥 PROTECCIÓN 3: Usar transacción para evitar condiciones de carrera
                            \DB::transaction(function () use ($user, $membershipPlan, $payment, $paymentId, $userId, $membershipPlanId) {
                                Log::info('BEFORE: About to process membership for user', [
                                    'user_id' => $userId, 
                                    'plan_id' => $membershipPlanId,
                                    'current_active_memberships' => $user->memberships()->where('is_active', true)->count()
                                ]);
                                
                                // Verificar una vez más dentro de la transacción
                                $existingActiveMemb = $user->memberships()
                                    ->where('is_active', true)
                                    ->where('created_at', '>=', now()->utc()->subMinutes(5))
                                    ->exists();
                                
                                if ($existingActiveMemb) {
                                    Log::info('Active membership already exists within transaction, skipping', ['user_id' => $userId]);
                                    return;
                                }

                                // Desactivar cualquier membresía activa existente del usuario
                                $deactivatedCount = $user->memberships()->where('is_active', true)->update(['is_active' => false]);
                                Log::info('STEP 1: Existing active memberships deactivated', [
                                    'user_id' => $userId,
                                    'deactivated_count' => $deactivatedCount
                                ]);

                                // Crear nueva membresía
                                $newMembership = $user->memberships()->create([
                                    'membership_plan_id' => $membershipPlan->id,
                                    'starts_at' => now()->utc(),
                                    'ends_at' => now()->utc()->addDays($membershipPlan->duration_days),
                                    'is_active' => true,
                                    'remaining_classes' => $membershipPlan->class_limit,
                                ]);
                                Log::info('STEP 2: New membership created', [
                                    'user_id' => $userId, 
                                    'plan_id' => $membershipPlanId,
                                    'new_membership_id' => $newMembership->id,
                                    'total_active_memberships_after' => $user->memberships()->where('is_active', true)->count()
                                ]);

                                // Verificar si de alguna manera se crearon múltiples
                                $activeMemberships = $user->memberships()->where('is_active', true)->get();
                                if ($activeMemberships->count() > 1) {
                                    Log::warning('ALERT: Multiple active memberships detected!', [
                                        'user_id' => $userId,
                                        'active_memberships' => $activeMemberships->pluck('id')->toArray(),
                                        'created_at_times' => $activeMemberships->pluck('created_at')->toArray()
                                    ]);
                                }

                                // 🔥 PROTECCIÓN 4: Usar updateOrCreate para evitar pagos duplicados
                                $paymentRecord = Payment::updateOrCreate(
                                    ['external_id' => $payment->id], // Buscar por external_id
                                    [
                                        'user_id' => $userId,
                                        'membership_plan_id' => $membershipPlanId,
                                        'amount' => $payment->transaction_amount,
                                        'currency' => $payment->currency_id,
                                        'payment_method' => $payment->payment_type_id,
                                        'status' => $payment->status,
                                        'external_reference' => $payment->external_reference,
                                        'external_status' => $payment->status_detail,
                                        'processed_at' => now()->utc(),
                                        'metadata' => is_array($payment->metadata) ? $payment->metadata : json_decode(json_encode($payment->metadata), true),
                                    ]
                                );
                                Log::info('STEP 3: Payment recorded in database', [
                                    'payment_id' => $paymentId, 
                                    'external_id' => $payment->id,
                                    'was_created' => $paymentRecord->wasRecentlyCreated
                                ]);
                            });
                        } else {
                            Log::warning('User or Membership Plan not found for payment', ['payment_id' => $paymentId, 'user_id' => $userId, 'membership_plan_id' => $membershipPlanId]);
                        }
                    } else {
                        Log::warning('Missing metadata for payment processing', ['payment_id' => $paymentId]);
                    }
                } else if ($payment && ($payment->status === 'pending' || $payment->status === 'in_process')) {
                    Log::info('Payment Pending or In Process', ['payment_id' => $paymentId]);
                    // Registrar el pago en la base de datos con estado pendiente
                    $this->recordPayment($payment);
                } else if ($payment && $payment->status === 'rejected') {
                    Log::warning('Payment Rejected', ['payment_id' => $paymentId, 'status_detail' => $payment->status_detail]);
                    // Registrar el pago en la base de datos con estado rechazado
                    $this->recordPayment($payment);
                }
            } catch (\MercadoPago\Exceptions\MPApiException $e) {
                $apiResponse = $e->getApiResponse();
                Log::error('Error processing Mercado Pago webhook from API', [
                    'payment_id' => $paymentId,
                    'error' => $e->getMessage(),
                    'api_response_status' => $apiResponse ? $apiResponse->getStatusCode() : 'N/A',
                    // 'api_response_headers' => $apiResponse ? $apiResponse->getHeaders() : 'N/A',
                    'api_response_content' => $apiResponse ? (is_array($apiResponse->getContent()) ? $apiResponse->getContent() : json_decode($apiResponse->getContent(), true)) : 'No API response content available',
                ]);
                return response()->json(['status' => 'error', 'message' => $e->getMessage(), 'details' => $apiResponse ? $apiResponse->getContent() : 'No details'], 500);    
            }
        }

        return response()->json(['status' => 'ok']);
    }

    /**
     * Registra un pago en la base de datos.
     */
    private function recordPayment($payment)
    {
        // Usar external_reference como respaldo
        $externalRef = $payment->external_reference;
        $parts = explode('-', $externalRef);
        
        $userId = $parts[0] ?? null;
        $membershipPlanId = $parts[1] ?? null;
        
        // Verificar metadata con múltiples métodos
        if ($payment->metadata) {
            if (is_object($payment->metadata)) {
                $userId = $payment->metadata->user_id ?? $userId;
                $membershipPlanId = $payment->metadata->membership_plan_id ?? $membershipPlanId;
            } elseif (is_array($payment->metadata)) {
                $userId = $payment->metadata['user_id'] ?? $userId;
                $membershipPlanId = $payment->metadata['membership_plan_id'] ?? $membershipPlanId;
            } else {
                $metadata = json_decode(json_encode($payment->metadata), true);
                $userId = $metadata['user_id'] ?? $userId;
                $membershipPlanId = $metadata['membership_plan_id'] ?? $membershipPlanId;
            }
        }

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
            'processed_at' => now()->utc(),
            'metadata' => is_array($payment->metadata) ? $payment->metadata : json_decode(json_encode($payment->metadata), true),
        ]);
        Log::info('Payment recorded in database with status', ['payment_id' => $payment->id, 'status' => $payment->status]);
    }

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

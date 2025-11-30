<?php

namespace App\Http\Controllers\Payments;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class PaymentController extends Controller
{
    /**
     * Mostrar lista de pagos con paginación y filtros
     */
    public function index(Request $request): Response
    {
        $query = Payment::with(['user', 'membershipPlan'])
            ->orderBy('created_at', 'desc');

        // Filtro por estado
        if ($request->filled('status')) {
            if($request->status !== 'all') {
              $query->where('status', $request->status);
            }
        }

        // Filtro por método de pago
        if ($request->filled('payment_method')) {
            if($request->payment_method !== 'all') {
              $query->where('payment_method', $request->payment_method);
            }
        }

        // Filtro por usuario (búsqueda por nombre)
        if ($request->filled('search')) {
            $query->whereHas('user', function($q) use ($request) {
                $q->where('name', 'ILIKE', '%' . $request->search . '%')
                  ->orWhere('email', 'ILIKE', '%' . $request->search . '%');
            });
        }

        // Filtro por rango de fechas
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $payments = $query->paginate(15);

        return Inertia::render('payments/Index', [
            'payments' => $payments,
            'filters' => $request->only(['status', 'payment_method', 'search', 'date_from', 'date_to']),
            'stats' => $this->getPaymentStats()
        ]);
    }

    /**
     * Mostrar detalles de un pago específico
     */
    public function show(Payment $payment): Response
    {
        $payment->load(['user', 'membershipPlan', 'membership']);

        return Inertia::render('Payments/Show', [
            'payment' => $payment,
        ]);
    }

    /**
     * Obtener estadísticas de pagos para el dashboard
     */
    private function getPaymentStats(): array
    {
        return [
            'total_payments' => Payment::count(),
            'approved_payments' => Payment::approved()->count(),
            'pending_payments' => Payment::pending()->count(),
            'failed_payments' => Payment::failed()->count(),
            'total_revenue' => Payment::approved()->sum('amount'),
            'monthly_revenue' => Payment::approved()
                ->whereMonth('created_at', now()->month)
                ->whereYear('created_at', now()->year)
                ->sum('amount'),
        ];
    }

    /**
     * Reembolsar un pago (marcar como refunded)
     */
    public function refund(Request $request, Payment $payment)
    {
        $request->validate([
            'reason' => 'required|string|max:255'
        ]);

        if ($payment->status !== 'approved') {
            return back()->withErrors(['error' => 'Solo se pueden reembolsar pagos aprobados']);
        }

        $payment->update([
            'status' => 'refunded',
            'external_status' => 'refunded',
            'metadata' => array_merge($payment->metadata ?? [], [
                'refund_reason' => $request->reason,
                'refunded_at' => now()->toISOString(),
                'refunded_by' => auth()->user()->id
            ])
        ]);

        // Desactivar la membresía asociada si existe
        if ($payment->user && $payment->user->membership) {
            $payment->user->membership->update(['is_active' => false]);
        }

        return back()->with('success', 'Pago reembolsado exitosamente');
    }

    /**
     * Exportar pagos a CSV
     */
    public function export(Request $request)
    {
        $query = Payment::with(['user', 'membershipPlan']);

        // Aplicar los mismos filtros que en index
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $payments = $query->orderBy('created_at', 'desc')->get();

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="payments_export_' . now()->format('Y-m-d') . '.csv"',
        ];

        $callback = function() use ($payments) {
            $file = fopen('php://output', 'w');
            
            // Encabezados del CSV
            fputcsv($file, [
                'ID',
                'Usuario',
                'Email',
                'Plan de Membresía',
                'Monto',
                'Moneda',
                'Método de Pago',
                'Estado',
                'ID Externo',
                'Referencia Externa',
                'Fecha de Procesamiento',
                'Fecha de Creación'
            ]);

            // Datos de los pagos
            foreach ($payments as $payment) {
                fputcsv($file, [
                    $payment->id,
                    $payment->user->name ?? 'N/A',
                    $payment->user->email ?? 'N/A',
                    $payment->membershipPlan->name ?? 'N/A',
                    $payment->amount,
                    $payment->currency,
                    $payment->payment_method,
                    $payment->status,
                    $payment->external_id,
                    $payment->external_reference,
                    $payment->processed_at?->format('Y-m-d H:i:s'),
                    $payment->created_at->format('Y-m-d H:i:s')
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Obtener resumen de pagos por mes para gráficos
     */
    public function monthlyReport(Request $request)
    {
        $year = $request->get('year', now()->year);
        
        $monthlyData = Payment::approved()
            ->selectRaw('EXTRACT(MONTH FROM created_at) as month, COUNT(*) as count, SUM(amount) as total')
            ->whereYear('created_at', $year)
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        // Llenar meses faltantes con 0
        $months = collect(range(1, 12))->map(function ($month) use ($monthlyData) {
            $found = $monthlyData->firstWhere('month', $month);
            return [
                'month' => $month,
                'month_name' => now()->month($month)->format('F'),
                'count' => $found ? $found->count : 0,
                'total' => $found ? $found->total : 0,
            ];
        });

        return response()->json($months);
    }
}
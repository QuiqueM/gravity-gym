<?php

namespace App\Console\Commands;

use App\Models\Membership;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class DeactivateExpiredMemberships extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:deactivate-expired-memberships';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Desactivar membresías que han expirado';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Iniciando revisión de membresías expiradas...');

        // Buscar membresías activas que ya expiraron
        $expiredMemberships = Membership::where('is_active', true)
            ->where('ends_at', '<', now())
            ->get();

        if ($expiredMemberships->isEmpty()) {
            $this->info('No se encontraron membresías expiradas.');
            Log::info('Cron memberships:deactivate-expired ejecutado - No hay membresías expiradas');
            return;
        }

        $count = 0;
        foreach ($expiredMemberships as $membership) {
            $membership->update(['is_active' => false]);
            $count++;
            
            $this->line("Desactivada membresía ID: {$membership->id} del usuario: {$membership->user->name}");
            
            // Log para auditoría
            Log::info('Membresía expirada desactivada', [
                'membership_id' => $membership->id,
                'user_id' => $membership->user_id,
                'user_name' => $membership->user->name,
                'expired_date' => $membership->ends_at,
                'deactivated_at' => now()
            ]);
        }

        $this->info("✅ Se desactivaron {$count} membresías expiradas.");
        Log::info("Cron memberships:deactivate-expired ejecutado exitosamente - {$count} membresías desactivadas");
    }
}

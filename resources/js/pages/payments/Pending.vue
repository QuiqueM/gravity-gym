<template>
  <div class="min-h-screen bg-background flex flex-col justify-center py-12 sm:px-6 lg:px-8">
    <div class="sm:mx-auto sm:w-full sm:max-w-md">
      <div class="bg-background border border-primary py-8 px-4 shadow-lg shadow-primary sm:rounded-lg sm:px-10">
        <!-- Icono de pendiente -->
        <div class="text-center">
          <div class="mx-auto flex items-center justify-center h-16 w-16 rounded-full bg-yellow-100 mb-4">
            <svg class="h-8 w-8 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
          </div>

          <h1 class="text-2xl font-bold text-primary mb-2">Pago Pendiente</h1>
          <p class="text-muted-foreground mb-6">Tu pago está siendo procesado</p>

          <!-- Información del pago -->
          <div class="bg-gray-50 rounded-lg p-4 mb-6">
            <h3 class="font-medium text-gray-900 mb-2">Detalles del pago:</h3>
            <div class="text-sm text-gray-600 space-y-1">
              <p v-if="payment_id">ID de pago: {{ payment_id }}</p>
              <p v-if="preference_id">Referencia: {{ preference_id }}</p>
              <p>Estado: <span class="text-yellow-600 font-medium">Pendiente</span></p>
            </div>
          </div>
          
          <!-- Información sobre pagos pendientes -->
          <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-6">
            <h3 class="font-medium text-blue-800 mb-2">¿Qué significa esto?</h3>
            <ul class="text-sm text-blue-700 space-y-1">
              <li>• Tu pago está siendo verificado</li>
              <li>• Puede tomar hasta 24 horas</li>
              <li>• Recibirás una notificación cuando se complete</li>
              <li>• Tu membresía se activará automáticamente</li>
            </ul>
          </div>
          
          <!-- Botones de acción -->
          <div class="space-y-3">
            <Link 
              :href="route('dashboard')" 
              class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-primary"
            >
              Ir al Dashboard
            </Link>
            
            <Link 
              :href="route('membership.my.show')" 
              class="w-full flex justify-center py-2 px-4 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500"
            >
              Ver Membresías
            </Link>
          </div>
          
          <!-- Información adicional -->
          <div class="mt-6 text-xs text-muted-foreground">
            <p>• Te notificaremos por correo cuando se complete</p>
            <p>• Puedes verificar el estado en tu perfil</p>
            <p>• Si tienes dudas, contacta a soporte</p>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { Link } from '@inertiajs/vue3';

defineProps<{
  payment_id?: string;
  preference_id?: string;
}>();
</script>

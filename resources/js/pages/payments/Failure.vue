<template>
  <div class="min-h-screen bg-gray-50 flex flex-col justify-center py-12 sm:px-6 lg:px-8">
    <div class="sm:mx-auto sm:w-full sm:max-w-md">
      <div class="bg-white py-8 px-4 shadow sm:rounded-lg sm:px-10">
        <!-- Icono de error -->
        <div class="text-center">
          <div class="mx-auto flex items-center justify-center h-16 w-16 rounded-full bg-red-100 mb-4">
            <svg class="h-8 w-8 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
            </svg>
          </div>
          
          <h1 class="text-2xl font-bold text-gray-900 mb-2">Pago No Procesado</h1>
          <p class="text-gray-600 mb-6">No pudimos procesar tu pago en este momento</p>
          
          <!-- Información del pago -->
          <div class="bg-gray-50 rounded-lg p-4 mb-6">
            <h3 class="font-medium text-gray-900 mb-2">Detalles:</h3>
            <div class="text-sm text-gray-600 space-y-1">
              <p v-if="payment_id">ID de pago: {{ payment_id }}</p>
              <p v-if="preference_id">Referencia: {{ preference_id }}</p>
              <p>Estado: <span class="text-red-600 font-medium">Fallido</span></p>
            </div>
          </div>
          
          <!-- Posibles causas -->
          <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4 mb-6">
            <h3 class="font-medium text-yellow-800 mb-2">Posibles causas:</h3>
            <ul class="text-sm text-yellow-700 space-y-1">
              <li>• Fondos insuficientes</li>
              <li>• Datos de tarjeta incorrectos</li>
              <li>• Tarjeta bloqueada o vencida</li>
              <li>• Problemas de conectividad</li>
            </ul>
          </div>
          
          <!-- Botones de acción -->
          <div class="space-y-3">
            <button 
              @click="retryPayment"
              class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500"
            >
              Intentar de Nuevo
            </button>
            
            <Link 
              :href="route('memberships.index')" 
              class="w-full flex justify-center py-2 px-4 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500"
            >
              Volver a Membresías
            </Link>
          </div>
          
          <!-- Información de contacto -->
          <div class="mt-6 text-xs text-gray-500">
            <p>¿Necesitas ayuda? Contacta a soporte:</p>
            <p class="font-medium">soporte@gravitygym.com</p>
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

function retryPayment() {
  // Volver a la página de membresías para intentar de nuevo
  window.location.href = route('memberships.index');
}
</script>

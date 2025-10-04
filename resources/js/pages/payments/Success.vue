<template>
  <div class="min-h-screen bg-gray-50 flex flex-col justify-center py-12 sm:px-6 lg:px-8">
    <div class="sm:mx-auto sm:w-full sm:max-w-md">
      <div class="bg-white py-8 px-4 shadow sm:rounded-lg sm:px-10">
        <!-- Icono de éxito -->
        <div class="text-center">
          <div class="mx-auto flex items-center justify-center h-16 w-16 rounded-full bg-green-100 mb-4">
            <svg class="h-8 w-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
            </svg>
          </div>
          
          <h1 class="text-2xl font-bold text-gray-900 mb-2">¡Pago Exitoso!</h1>
          <p class="text-gray-600 mb-6">Tu membresía ha sido activada correctamente</p>
          
          <!-- Información del pago -->
          <div class="bg-gray-50 rounded-lg p-4 mb-6">
            <h3 class="font-medium text-gray-900 mb-2">Detalles del pago:</h3>
            <div class="text-sm text-gray-600 space-y-1">
              <p v-if="payment_id">ID de pago: {{ payment_id }}</p>
              <p v-if="preference_id">Referencia: {{ preference_id }}</p>
              <p>Estado: <span class="text-green-600 font-medium">Aprobado</span></p>
            </div>
          </div>
          
          <!-- Botones de acción -->
          <div class="space-y-3">
            <Link 
              :href="route('dashboard')" 
              class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500"
            >
              Ir al Dashboard
            </Link>
            
            <Link 
              :href="route('memberships.index')" 
              class="w-full flex justify-center py-2 px-4 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500"
            >
              Ver Membresías
            </Link>
          </div>
          
          <!-- Información adicional -->
          <div class="mt-6 text-xs text-gray-500">
            <p>• Recibirás un correo de confirmación</p>
            <p>• Tu membresía está activa desde ahora</p>
            <p>• Puedes acceder a todas las clases disponibles</p>
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

<template>
  <div v-if="open" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-40">
    <div class="bg-white rounded-lg shadow-lg p-6 w-full max-w-md relative">
      <button class="absolute top-2 right-2 text-gray-400 hover:text-gray-600" @click="$emit('close')">&times;</button>
      <h2 class="text-xl font-bold mb-4">Pagar membresía</h2>
      
      <!-- Estado de carga -->
      <div v-if="loading" class="text-center py-8">
        <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-blue-600 mx-auto mb-4"></div>
        <span class="text-gray-500">Generando enlace de pago...</span>
      </div>
      
      <!-- Estado de error -->
      <div v-else-if="error" class="text-red-500 text-center py-4">
        <div class="mb-4">
          <svg class="w-12 h-12 mx-auto text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
          </svg>
        </div>
        <p class="font-medium">{{ error }}</p>
        <button 
          class="mt-4 px-4 py-2 bg-red-500 text-white rounded hover:bg-red-600 transition-colors"
          @click="retryPayment"
        >
          Intentar de nuevo
        </button>
      </div>
      
      <!-- Estado de éxito - Mostrar enlace de pago -->
      <div v-else-if="initPoint" class="text-center">
        <div class="mb-4">
          <svg class="w-12 h-12 mx-auto text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
          </svg>
        </div>
        <p class="text-gray-700 mb-4">¡Enlace de pago generado exitosamente!</p>
        <p class="text-sm text-gray-500 mb-6">Serás redirigido a Mercado Pago para completar el pago</p>
        
        <!-- Botón para abrir checkout -->
        <button 
          class="w-full bg-blue-600 text-white py-3 px-6 rounded-lg hover:bg-blue-700 transition-colors font-medium"
          @click="openCheckout"
        >
          Pagar con Mercado Pago
        </button>
        
        <!-- Información adicional -->
        <div class="mt-4 text-xs text-gray-500">
          <p>• Pagos seguros con Mercado Pago</p>
          <p>• Acepta tarjetas de crédito y débito</p>
          <p>• Hasta 12 meses sin intereses</p>
        </div>
      </div>
      
      <!-- Estado inicial -->
      <div v-else class="text-center">
        <div class="mb-6">
          <h3 class="text-lg font-medium text-gray-900 mb-2">{{ plan?.name || 'Plan de Membresía' }}</h3>
          <p class="text-2xl font-bold text-blue-600">${{ amount.toLocaleString('es-MX') }} MXN</p>
        </div>
        
        <button 
          class="w-full bg-blue-600 text-white py-3 px-6 rounded-lg hover:bg-blue-700 transition-colors font-medium"
          @click="createPayment" 
          :disabled="loading"
        >
          Generar enlace de pago
        </button>
        
        <p class="mt-4 text-sm text-gray-500">
          Al continuar, serás redirigido a Mercado Pago para completar tu pago de forma segura.
        </p>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref, watch } from 'vue';
import { router } from '@inertiajs/vue3';

const props = defineProps<{
  open: boolean,
  plan: any,
  amount: number,
}>();

const emit = defineEmits(['close']);

const loading = ref(false);
const error = ref('');
const initPoint = ref('');
const paymentId = ref('');

watch(() => props.open, (val) => {
  if (!val) {
    loading.value = false;
    error.value = '';
    initPoint.value = '';
    paymentId.value = '';
  }
});

function createPayment() {
  loading.value = true;
  error.value = '';
  router.post(
    '/payments/mercadopago/membership',
    {
      plan: {
        id: 1,
        name: 'Membresía',
      },
      amount: 1,
    },
    {
      preserveScroll: true,
      onSuccess: (page) => {
        if (page.props && page.props.init_point) {
          initPoint.value = String(page.props.init_point);
          paymentId.value = page.props.payment_id || '';
        } else {
          error.value = 'No se pudo obtener el enlace de pago.';
        }
      },
      onError: (errs) => {
        error.value = errs.message || 'Error al generar el pago.';
      },
      onFinish: () => {
        loading.value = false;
      },
    }
  );
}

function openCheckout() {
  if (initPoint.value) {
    // Abrir en nueva ventana para mejor UX
    const checkoutWindow = window.open(
      initPoint.value, 
      'mercadopago-checkout', 
      'width=800,height=600,scrollbars=yes,resizable=yes'
    );
    
    // Monitorear si la ventana se cierra
    const checkClosed = setInterval(() => {
      if (checkoutWindow?.closed) {
        clearInterval(checkClosed);
        // Aquí podrías verificar el estado del pago
        emit('close');
      }
    }, 1000);
  }
}

function retryPayment() {
  error.value = '';
  initPoint.value = '';
  paymentId.value = '';
  createPayment();
}
</script>

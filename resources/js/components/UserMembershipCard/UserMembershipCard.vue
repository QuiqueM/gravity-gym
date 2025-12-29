<template>
  <div class="card-container">
    <div class="flex justify-end items-start ">
      <!-- <img src="https://img.icons8.com/plasticine/100/sim-card-chip.png" alt="Chip" class="w-16 h-auto" /> -->
      
      <div class="logo">
        <img src="/assets/logoNavbar.png" class="w-40" alt="logo gravity">
      </div>
    </div>

    <div class="text-3xl font-mono tracking-widest mb-6">
      {{ formattedCardNumber }}
    </div>

    <div class="flex justify-between items-end">
      <div class="flex flex-col">
        <span class="text-xs uppercase text-gray-300">Titular</span>
        <span class="text-lg font-medium tracking-wide">{{ cardholderName }}</span>
        <span class="text-sm font-mono mt-1">{{ expiryDate }}</span>
      </div>
      
      <div class="qr-code-wrapper bg-white p-1 rounded-md size-20">
        <img :src="qrCodeData" alt="QR Code" class="" />
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { computed } from 'vue';

// Definimos las props que el componente recibirá del padre
const props = defineProps<{
  cardholderName: string;
  cardNumber: string;
  expiryDate: string;
  cardType: 'visa' | 'mastercard'; // Solo acepta estos dos tipos
  qrCodeData: string; // El texto o URL que se codificará en el QR
}>();

// Propiedad computada para formatear el número de tarjeta en grupos de 4
const formattedCardNumber = computed(() => {
  return props.cardNumber.replace(/\s?/g, '').replace(/(\d{4})/g, '$1 ').trim();
});
</script>

<style scoped>
.card-container {
  width: 100%;
  max-width: 400px;
  aspect-ratio: 1.586 / 1; /* Proporción estándar de una tarjeta de crédito */
  background-image: linear-gradient(135deg, #000000 60%, #22223b 85%, #3b82f6 100%);
  border-radius: 1rem;
  padding: 1rem;
  color: white;
  box-shadow:
    0 8px 32px 8px rgba(68, 68, 68, 0.45), /* sombra principal más profunda */
    0 1.5px 6px 0 rgba(59,130,246,0.12), /* brillo azul sutil */
    0 0.5px 2px 0 rgba(255,255,255,0.08); /* reflejo blanco suave */
  display: flex;
  flex-direction: column;
  justify-content: space-between;
}
</style>
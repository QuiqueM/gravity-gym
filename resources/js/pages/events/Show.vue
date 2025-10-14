<template>
  <div>
    <h1 class="text-2xl font-bold mb-4">Detalle del evento</h1>
    <div class="mb-4">
      <strong>Título:</strong> {{ event.title }}
    </div>
    <div class="mb-4">
      <strong>Descripción:</strong> {{ event.description }}
    </div>
    <div class="mb-4">
      <strong>Clase:</strong> {{ event.gym_class?.name }}
    </div>
    <div class="mb-4">
      <strong>Imagen:</strong>
      <img v-if="event.image" :src="`/storage/${event.image}`" alt="Imagen" class="h-32 w-32 object-cover rounded" />
    </div>
    <Link :href="route('admin.events.index')" class="btn btn-secondary">Volver</Link>
  </div>
</template>

<script setup>
import { Link } from '@inertiajs/vue3';
import { defineProps } from 'vue';
const props = defineProps({ event: Object });
</script>

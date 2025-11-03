<template>
  <div>
    <h1 class="text-2xl font-bold mb-4">Editar evento</h1>
    <form @submit.prevent="submit">
      <div class="mb-4">
        <label class="block mb-1">Título</label>
        <input v-model="form.title" type="text" class="input input-bordered w-full" required />
      </div>
      <div class="mb-4">
        <label class="block mb-1">Descripción</label>
        <textarea v-model="form.description" class="input input-bordered w-full" required></textarea>
      </div>
      <div class="mb-4">
        <label class="block mb-1">Imagen</label>
        <input type="file" @change="onFileChange" class="input input-bordered w-full" />
        <img v-if="event.image" :src="`/storage/${event.image}`" alt="Imagen actual" class="h-16 w-16 object-cover rounded mt-2" />
      </div>
      <div class="mb-4">
        <label class="block mb-1">Clase</label>
        <select v-model="form.class_id" class="input input-bordered w-full" required>
          <option value="" disabled>Selecciona una clase</option>
          <option v-for="c in classes" :key="c.id" :value="c.id">{{ c.title }}</option>
        </select>
      </div>
      <button type="submit" class="btn btn-primary">Actualizar</button>
      <Link :href="route('events.index')" class="btn btn-secondary ml-2">Cancelar</Link>
    </form>
  </div>
</template>

<script lang="ts" setup>
import { Link, useForm } from '@inertiajs/vue3';
import { defineProps } from 'vue';
import type { Classes } from '@/types/Class';

const props = defineProps<{
  event: {
    id: number;
    title: string;
    description: string;
    image: string | null;
    class_id: number;
  };
  classes: Classes[];
}>();
const form = useForm({
  title: props.event.title,
  description: props.event.description,
  image: null as File | null,
  class_id: props.event.class_id
});
function onFileChange(e: Event) {
  const target = e.target as HTMLInputElement;
  if (target.files && target.files.length > 0) {
    form.image = target.files[0];
  } else {
    form.image = null;
  }
}
function submit() {
  form.post(route('events.update', props.event.id), {
    preserveScroll: true,
    onSuccess: () => form.reset('image'),
    method: 'put'
  });
}
</script>

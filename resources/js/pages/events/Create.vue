<template>
  <Head title="Crear Evento" />
  <AppLayout :breadcrumbs="breadcrumbs">
  <div class="p-4 space-y-6">
    <h1 class="text-2xl font-bold mb-4">Crear evento</h1>
    <form @submit.prevent="submit" class="flex flex-col gap-6 w-full mx-auto mt-8">
      <div class="flex flex-col gap-2">
        <Label class="">Título</Label>
        <Input v-model="form.title" type="text" class="input input-bordered w-full" required />
      </div>
      <div class="flex flex-col gap-2">
        <Label class="">Descripción</Label>
        <Textarea v-model="form.description" class="input input-bordered w-full" required></Textarea>
      </div>
      <div class="flex flex-col gap-2">
        <Label class="">Imagen</Label>
        <Input type="file" @change="onFileChange" class="input input-bordered w-full" />
      </div>
      <div class="flex flex-col gap-2">
        <Label class="">Clase</Label>
        <Select id="class" v-model="form.class_id">
          <SelectTrigger class="w-full">
            <SelectValue placeholder="Selecciona una clase" />
          </SelectTrigger>
          <SelectContent>
            <SelectItem v-for="c in classes" :key="c.id" :value="c.id">{{ c.title }}</SelectItem>
          </SelectContent>
        </Select>
      </div>
      <div class="flex items-center justify-between">
        <Link :href="route('events.index')">
          <Button variant="secondary">Cancelar</Button>
        </Link>
        <Button type="submit" class="btn btn-primary">Guardar</Button>
      </div>
    </form>
  </div>
  </AppLayout>
</template>

<script setup lang="ts">
import { Link, useForm, Head } from '@inertiajs/vue3';
import { defineProps } from 'vue';
import AppLayout from '@/layouts/AppLayout.vue';
import { Label } from '@/components/ui/label';
import { Input } from '@/components/ui/input';
import { Textarea } from '@/components/ui/textarea';
import {
  Select,
  SelectContent,
  SelectItem,
  SelectTrigger,
  SelectValue,
} from "@/components/ui/select"
import { Button } from "@/components/ui/button"
import type { BreadcrumbItem } from '@/types';
import type { Classes } from '@/types/Class';
import { toast } from 'vue-sonner';

defineProps<{
  classes: Classes[];
}>();

const breadcrumbs: BreadcrumbItem[] = [
  { title: 'Eventos', href: '/events' },
  { title: 'Crear evento', href: '#' },
];
const form = useForm({
  title: '',
  description: '',
  image: null as File | null,
  class_id: ''
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
  form.post(route('events.store'), {
    preserveScroll: true,
    onSuccess: () => {
      form.reset('image', 'title', 'description', 'class_id');
      toast.success('Evento creado correctamente');
    },
    onError: () => {
      toast.error('Error al crear el evento');
    },
  });
}
</script>

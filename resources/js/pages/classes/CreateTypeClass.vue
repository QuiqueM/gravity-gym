<script setup lang="ts">
import InputError from '@/components/InputError.vue';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import AppLayout from '@/layouts/AppLayout.vue';
import { Head, useForm, Link } from '@inertiajs/vue3';
import { LoaderCircle } from 'lucide-vue-next';
import type { BreadcrumbItem } from '@/types';
import { toast } from 'vue-sonner'

const breadcrumbs: BreadcrumbItem[] = [
  { title: 'Dashboard', href: '/dashboard' },
  { title: 'Clases', href: '/classes' },
  { title: 'Crear Categoria', href: '#' },
];

const form = useForm({
  name: '',
  description: '',
});

const submit = () => {
  form.post(route('classes.type.store'), {
    onSuccess: () => {
      toast.success('Categoría creada exitosamente');
      form.reset();
    },
    onError: () => {
      toast.error('Ocurrió un error al crear la categoría');
    },
  });
};
</script>

<template>

  <Head title="Crear plan de membresía" />
  <AppLayout :breadcrumbs="breadcrumbs">
    <div class="p-4 space-y-6">
      <form method="POST" @submit.prevent="submit" class="flex flex-col gap-6 w-full mx-auto mt-8">
        <div class="grid gap-6">
          <div class="grid gap-2">
            <Label for="name">Nombre *</Label>
            <Input id="name" type="text" required autofocus autocomplete="off" v-model="form.name"
              placeholder="Calistenia" />
            <InputError :message="form.errors.name" />
          </div>
          <div class="grid gap-2">
            <Label for="description">Descripción</Label>
            <Input id="description" type="text" autocomplete="off" v-model="form.description"
              placeholder="Clase matutina de calistenia" />
            <InputError :message="form.errors.description" />
          </div>
          
          <div class="flex flex-col md:flex-row md:justify-between items-center gap-3">
            <Link :href="route('classes.index')" class="w-full md:w-48">
              <Button variant="outline" class="w-full md:w-48" >Cancelar</Button>
            </Link>

            <Button type="submit" class="w-full md:w-48" :disabled="form.processing">
              <LoaderCircle v-if="form.processing" class="h-4 w-4 animate-spin" />
              Crear Categoría
            </Button>
          </div>
        </div>
      </form>
    </div>
  </AppLayout>
</template>
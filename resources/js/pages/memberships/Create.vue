<script setup lang="ts">
import InputError from '@/components/InputError.vue';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import AppLayout from '@/layouts/AppLayout.vue';
import { Head, useForm } from '@inertiajs/vue3';
import { LoaderCircle } from 'lucide-vue-next';
import {
  NumberField,
  NumberFieldContent,
  NumberFieldDecrement,
  NumberFieldIncrement,
  NumberFieldInput,
} from "@/components/ui/number-field"
import { toast } from 'vue-sonner'

const breadcrumbs = [
  { title: 'Dashboard', href: '/dashboard' },
  { title: 'Membresías', href: '/memberships' },
  { title: 'Crear Membresía', href: '#' },
];

const form = useForm({
  name: '',
  description: '',
  duration_days: 30,
  class_limit_per_week: 7,
  price: 50,
  is_active: true,
});

const submit = () => {
  form.post(route('memberships-plan.store'), {
    onSuccess: () => {
      toast.success('Plan de membresía creado exitosamente');
      form.reset();
    },
    onError: () => {
      toast.error('Ocurrió un error al crear el plan de membresía');
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
            <Label for="name">Nombre</Label>
            <Input id="name" type="text" required autofocus autocomplete="off" v-model="form.name"
              placeholder="Nombre del plan" />
            <InputError :message="form.errors.name" />
          </div>
          <div class="grid gap-2">
            <Label for="description">Descripción</Label>
            <Input id="description" type="text" autocomplete="off" v-model="form.description"
              placeholder="Descripción del plan" />
            <InputError :message="form.errors.description" />
          </div>
          <div class="grid gap-2">
            <Label for="duration_days">Duración (días)</Label>
            <NumberField id="duration_days" v-model="form.duration_days" :min="1" :step="1" required>
              <NumberFieldContent>
                <NumberFieldDecrement />
                <NumberFieldInput />
                <NumberFieldIncrement />
              </NumberFieldContent>
            </NumberField>
            <InputError :message="form.errors.duration_days" />
          </div>
          <div class="grid gap-2">
            <Label for="class_limit_per_week">Límite de clases por semana</Label>
            <NumberField id="class_limit_per_week" v-model="form.class_limit_per_week" :min="1" :step="1" required>
              <NumberFieldContent>
                <NumberFieldDecrement />
                <NumberFieldInput />
                <NumberFieldIncrement />
              </NumberFieldContent>
            </NumberField>
            <InputError :message="form.errors.class_limit_per_week" />
          </div>
          <div class="grid gap-2">
            <Label for="price">Precio</Label>
            <NumberField id="price" v-model="form.price" :min="50" :step="50" required :format-options="{
              style: 'currency',
              currency: 'MXN',
              currencyDisplay: 'code',
              currencySign: 'accounting',
            }">
              <NumberFieldContent>
                <NumberFieldDecrement />
                <NumberFieldInput />
                <NumberFieldIncrement />
              </NumberFieldContent>
            </NumberField>
            <InputError :message="form.errors.price" />
          </div>
          <div class="flex items-center gap-2">
            <input id="is_active" type="checkbox" v-model="form.is_active" class="accent-primary" />
            <Label for="is_active">Activo</Label>
          </div>
          <div class="flex md:justify-end">
            <Button type="submit" class="mt-2 w-full md:w-48" :disabled="form.processing">
              <LoaderCircle v-if="form.processing" class="h-4 w-4 animate-spin" />
              Crear plan
            </Button>
          </div>
        </div>
      </form>
    </div>
  </AppLayout>
</template>
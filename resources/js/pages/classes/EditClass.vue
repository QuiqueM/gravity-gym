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
import type { EditClassProps } from '@/types/Class';
import {
  Select,
  SelectContent,
  SelectItem,
  SelectTrigger,
  SelectValue,
} from "@/components/ui/select"
import { Checkbox } from "@/components/ui/checkbox"
import {
  NumberField,
  NumberFieldContent,
  NumberFieldDecrement,
  NumberFieldIncrement,
  NumberFieldInput,
} from "@/components/ui/number-field"

const breadcrumbs: BreadcrumbItem[] = [
  { title: 'Dashboard', href: '/dashboard' },
  { title: 'Clases', href: '/classes' },
  { title: 'Crear Clase', href: '#' },
];

const props = defineProps<EditClassProps>();

const form = useForm({
  title: props.classSelected.title,
  class_type_id: props.classSelected.class_type_id,
  instructor_id: props.classSelected.instructor_id,
  capacity: props.classSelected.capacity,
  requires_membership: props.classSelected.requires_membership,
});

const submit = () => {
  form.put(route('classes.update', props.classSelected.id), {
    onSuccess: () => {
      toast.success('Clase actualizada exitosamente');
      form.reset();
    },
    onError: () => {
      toast.error('Ocurrió un error al actualizar la clase');
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
            <Label for="title">Titulo *</Label>
            <Input id="title" type="text" required autofocus autocomplete="off" v-model="form.title"
              placeholder="Calistenia" />
            <InputError :message="form.errors.title" />
          </div>
          <div class="grid gap-2">
            <Label for="category">Categoría *</Label>
            <Select id="category" v-model="form.class_type_id">
              <SelectTrigger class="w-full">
                <SelectValue placeholder="Selecciona una categoría" />
              </SelectTrigger>
              <SelectContent>
                <SelectItem v-for="type in types" :key="type.id" :value="type.id">{{ type.name }}</SelectItem>
              </SelectContent>
            </Select>
          </div>
          <div class="grid gap-2">
            <Label for="coach">Coach *</Label>
            <Select id="coach" v-model="form.instructor_id">
              <SelectTrigger class="w-full">
                <SelectValue placeholder="Selecciona un coach" />
              </SelectTrigger>
              <SelectContent>
                <SelectItem v-for="coach in coaches" :key="coach.id" :value="coach.id">{{ coach.name }}</SelectItem>
              </SelectContent>
            </Select>
          </div>

          <div class="grid gap-2">
            <Label for="capacity">Capacidad</Label>
            <NumberField id="capacity" v-model="form.capacity" :min="1" :step="1" required>
              <NumberFieldContent>
                <NumberFieldDecrement />
                <NumberFieldInput />
                <NumberFieldIncrement />
              </NumberFieldContent>
            </NumberField>
            <InputError :message="form.errors.capacity" />
          </div>

          <div class="flex items-center space-x-2">
            <Checkbox id="requires_membership" v-model="form.requires_membership" />
            <Label for="requires_membership" class="mb-0">Requiere membresía</Label>
          </div>

          <div class="flex flex-col md:flex-row md:justify-between items-center gap-3">
            <Link :href="route('classes.index')" class="w-full md:w-48">
              <Button variant="outline" class="w-full md:w-48">Cancelar</Button>
            </Link>

            <Button type="submit" class="w-full md:w-48" :disabled="form.processing">
              <LoaderCircle v-if="form.processing" class="h-4 w-4 animate-spin" />
              Guardar
            </Button>
          </div>
        </div>
      </form>
    </div>
  </AppLayout>
</template>
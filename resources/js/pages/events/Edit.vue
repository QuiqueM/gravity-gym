<template>
    <Head title="Editar Evento" />
    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="space-y-6 p-4">
            <h1 class="mb-4 text-2xl font-bold">Editar evento</h1>
            <form @submit.prevent="submit" class="mx-auto mt-8 flex w-full flex-col gap-6">
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

<script lang="ts" setup>
import { Link, useForm } from '@inertiajs/vue3';
import { defineProps } from 'vue';
import type { Classes } from '@/types/Class';
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
import { toast } from 'vue-sonner';

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

const breadcrumbs: BreadcrumbItem[] = [
  { title: 'Eventos', href: '/events' },
  { title: 'Editar evento', href: '#' },
];
const form = useForm({
  _method: 'put',
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
    onSuccess: () => {
      toast.success('Ejercicio actualizado exitosamente');
    },
    onError: () => {
      toast.error('Ocurrió un error al actualizar el ejercicio');
    },
  });
}
</script>

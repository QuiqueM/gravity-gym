<script lang="ts" setup>
import AppLayout from '@/layouts/AppLayout.vue';
import { Head, useForm, Link } from '@inertiajs/vue3';
import type { BreadcrumbItem } from '@/types';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Button } from '@/components/ui/button';
import InputError from '@/components/InputError.vue';
import { LoaderCircle } from 'lucide-vue-next';
import { toast } from 'vue-sonner';
import { Textarea } from '@/components/ui/textarea';
import {
    Select,
    SelectContent,
    SelectItem,
    SelectTrigger,
    SelectValue,
} from "@/components/ui/select";
import type { Category } from '@/types/Exercise';

defineProps<{
    categories: Category[];
}>();

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Dashboard', href: '/dashboard' },
    { title: 'Ejercicios', href: '/exercises' },
    { title: 'Crear', href: '#' },
];

const form = useForm({
    name: '',
    description: '',
    demonstration_video: null as File | null,
    category_id: null as number | null,
});

function onFileChange(e: Event) {
  const target = e.target as HTMLInputElement;
  if (target.files && target.files.length > 0) {
    form.demonstration_video = target.files[0];
  } else {
    form.demonstration_video = null;
  }
}


const submit = () => {
    form.post(route('admin.exercises.store'), {
        forceFormData: true,
        onSuccess: () => {
            toast.success('Ejercicio creado exitosamente');
            form.reset();
        },
        onError: () => {
            toast.error('Ocurrió un error al crear el ejercicio');
        },
    });
};
</script>

<template>
    <Head title="Crear ejercicio" />
    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="p-4 space-y-6">
            <div class="flex items-center justify-between">
                <h1 class="text-xl font-semibold">Crear ejercicio</h1>
            </div>

            <form method="POST" @submit.prevent="submit" class="flex flex-col gap-6">
                <div class="grid gap-6">
                    <div class="grid gap-2">
                        <Label for="name">Nombre</Label>
                        <Input id="name" type="text" required autofocus autocomplete="name" v-model="form.name"
                            placeholder="Nombre del ejercicio" />
                        <InputError :message="form.errors.name" />
                    </div>

                    <div class="grid gap-2">
                        <Label for="description">Descripción</Label>
                        <Textarea id="description" v-model="form.description" placeholder="Descripción del ejercicio" />
                        <InputError :message="form.errors.description" />
                    </div>

                    <div class="grid gap-2">
                        <Label for="demonstration_video">Video Demostrativo</Label>
                        <Input id="demonstration_video" type="file"  @change="onFileChange" />
                        <InputError :message="form.errors.demonstration_video" />
                    </div>

                    <div class="flex flex-col gap-2">
                        <Label for="category_id">Categoría</Label>
                        <Select id="category_id" v-model.number="form.category_id">
                            <SelectTrigger class="w-full">
                                <SelectValue placeholder="Seleccionar categoría" />
                            </SelectTrigger>
                            <SelectContent>
                                <SelectItem v-for="category in categories" :key="category.id" :value="category.id">
                                    {{ category.name }}
                                </SelectItem>
                            </SelectContent>
                        </Select>
                        <InputError :message="form.errors.category_id" />
                    </div>

                    <div class="flex flex-col md:flex-row md:justify-between items-center gap-3">
                        <Link :href="route('exercises.index')" class="w-full md:w-48">
                        <Button variant="outline" class="w-full md:w-48">Cancelar</Button>
                        </Link>
                        <Button type="submit" class="w-full md:w-48" :disabled="form.processing">
                            <LoaderCircle v-if="form.processing" class="h-4 w-4 animate-spin" />
                            Crear
                        </Button>
                    </div>
                </div>
            </form>

        </div>
    </AppLayout>
</template>

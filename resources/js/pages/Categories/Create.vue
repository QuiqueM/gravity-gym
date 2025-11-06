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

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Dashboard', href: '/dashboard' },
    { title: 'Categorías', href: '/categories' },
    { title: 'Crear', href: '#' },
];

const form = useForm({
    name: '',
    description: '',
});

const submit = () => {
    form.post(route('admin.categories.store'), {
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
    <Head title="Crear categoría" />
    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="p-4 space-y-6">
            <div class="flex items-center justify-between">
                <h1 class="text-xl font-semibold">Crear categoría</h1>
            </div>

            <form method="POST" @submit.prevent="submit" class="flex flex-col gap-6">
                <div class="grid gap-6">
                    <div class="grid gap-2">
                        <Label for="name">Nombre</Label>
                        <Input id="name" type="text" required autofocus autocomplete="name" v-model="form.name"
                            placeholder="Nombre de la categoría" />
                        <InputError :message="form.errors.name" />
                    </div>

                    <div class="grid gap-2">
                        <Label for="description">Descripción</Label>
                        <Textarea id="description" v-model="form.description" placeholder="Descripción de la categoría" />
                        <InputError :message="form.errors.description" />
                    </div>

                    <div class="flex flex-col md:flex-row md:justify-between items-center gap-3">
                        <Link :href="route('categories.index')" class="w-full md:w-48">
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

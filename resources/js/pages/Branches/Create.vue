<script lang="ts" setup>
import AppLayout from '@/layouts/AppLayout.vue';
import { Head, useForm, Link } from '@inertiajs/vue3';
import type { BreadcrumbItem } from '@/types';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Button } from '@/components/ui/button';
import InputError from '@/components/InputError.vue';
import { LoaderCircle } from 'lucide-vue-next';
import { Checkbox } from '@/components/ui/checkbox';
import { toast } from 'vue-sonner';

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Dashboard', href: '/dashboard' },
    { title: 'Sucursales', href: '/admin/branches' },
    { title: 'Crear', href: '#' },
];

const form = useForm({
    name: '',
    address: '',
    is_active: true,
    image: null as File | null,
});

const submit = () => {
    form.post(route('admin.branches.store'), {
        onSuccess: () => {
            toast.success('Sucursal creada exitosamente');
            form.reset();
        },
        onError: () => {
            toast.error('Ocurrió un error al crear la sucursal');
        },
    });
};
</script>

<template>
    <Head title="Crear sucursal" />
    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="p-4 space-y-6">
            <div class="flex items-center justify-between">
                <h1 class="text-xl font-semibold">Crear sucursal</h1>
            </div>

            <form method="POST" @submit.prevent="submit" class="flex flex-col gap-6">
                <div class="grid gap-6">
                    <div class="grid gap-2">
                        <Label for="name">Nombre</Label>
                        <Input id="name" type="text" required autofocus autocomplete="name" v-model="form.name"
                            placeholder="Nombre de la sucursal" />
                        <InputError :message="form.errors.name" />
                    </div>

                    <div class="grid gap-2">
                        <Label for="address">Dirección</Label>
                        <Input id="address" type="text" autocomplete="address" v-model="form.address"
                            placeholder="Dirección de la sucursal" />
                        <InputError :message="form.errors.address" />
                    </div>

                    <div class="flex items-center space-x-2">
                        <Checkbox id="is_active" v-model:checked="form.is_active" />
                        <Label for="is_active">Activa</Label>
                        <InputError :message="form.errors.is_active" />
                    </div>

                    <div class="grid gap-2">
                        <Label for="image">Imagen</Label>
                        <Input id="image" type="file" @input="form.image = $event.target.files[0]" />
                        <InputError :message="form.errors.image" />
                    </div>

                    <div class="flex flex-col md:flex-row md:justify-between items-center gap-3">
                        <Link :href="route('admin.branches.index')" class="w-full md:w-48">
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

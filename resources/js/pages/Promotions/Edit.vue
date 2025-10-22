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
import { Textarea } from '@/components/ui/textarea';

interface Promotion {
    id: number;
    name: string;
    description: string | null;
    is_active: boolean;
    image: string | null;
    created_at: string;
    updated_at: string;
}

const props = defineProps<{
    promotion: Promotion;
}>();

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Dashboard', href: '/dashboard' },
    { title: 'Promociones', href: '/admin/promotions' },
    { title: 'Editar', href: '#' },
];

const form = useForm({
    _method: 'put',
    name: props.promotion.name,
    description: props.promotion.description,
    is_active: props.promotion.is_active,
    image: null as File | null,
});

const submit = () => {
    form.post(route('admin.promotions.update', props.promotion.id), {
        onSuccess: () => {
            toast.success('Promoción actualizada exitosamente');
        },
        onError: () => {
            toast.error('Ocurrió un error al actualizar la promoción');
        },
    });
};
</script>

<template>
    <Head title="Editar promoción" />
    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="p-4 space-y-6">
            <div class="flex items-center justify-between">
                <h1 class="text-xl font-semibold">Editar promoción</h1>
            </div>

            <form method="POST" @submit.prevent="submit" class="flex flex-col gap-6">
                <div class="grid gap-6">
                    <div class="grid gap-2">
                        <Label for="name">Nombre</Label>
                        <Input id="name" type="text" required autofocus autocomplete="name" v-model="form.name"
                            placeholder="Nombre de la promoción" />
                        <InputError :message="form.errors.name" />
                    </div>

                    <div class="grid gap-2">
                        <Label for="description">Descripción</Label>
                        <Textarea id="description" v-model="form.description" placeholder="Descripción de la promoción" />
                        <InputError :message="form.errors.description" />
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
                        <img v-if="props.promotion.image" :src="`/storage/${props.promotion.image}`" alt="Promotion Image" class="mt-2 h-24 w-24 object-cover" />
                    </div>

                    <div class="flex flex-col md:flex-row md:justify-between items-center gap-3">
                        <Link :href="route('admin.promotions.index')" class="w-full md:w-48">
                        <Button variant="outline" class="w-full md:w-48">Cancelar</Button>
                        </Link>
                        <Button type="submit" class="w-full md:w-48" :disabled="form.processing">
                            <LoaderCircle v-if="form.processing" class="h-4 w-4 animate-spin" />
                            Actualizar
                        </Button>
                    </div>
                </div>
            </form>

        </div>
    </AppLayout>
</template>

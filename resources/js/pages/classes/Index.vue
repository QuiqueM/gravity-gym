<script setup lang="ts">
// @ts-nocheck
import AppLayout from '@/layouts/AppLayout.vue';
import { Head, Link } from '@inertiajs/vue3';
import type { BreadcrumbItem } from '@/types';

defineProps<{
    types: { id: number; name: string }[];
    classes: { data: { id: number; title: string; type?: { name: string } }[] };
}>();

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Dashboard', href: '/dashboard' },
    { title: 'Clases', href: '/classes' },
];
</script>

<template>
    <Head title="Clases" />
    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="p-4 space-y-6">
            <div class="grid gap-4 md:grid-cols-3">
                <div v-for="type in types" :key="type.id" class="rounded-xl border p-4">
                    <div class="font-medium">{{ type.name }}</div>
                </div>
            </div>

            <div>
                <h2 class="text-xl font-semibold mb-2">Listado</h2>
                <div class="rounded-xl border divide-y">
                    <div
                        v-for="item in classes.data"
                        :key="item.id"
                        class="flex items-center justify-between p-3"
                    >
                        <div>
                            <div class="font-medium">{{ item.title }}</div>
                            <div class="text-sm text-muted-foreground">{{ item.type?.name }}</div>
                        </div>
                        <Link :href="`/classes/${item.id}/schedules`" class="text-primary">Ver horarios</Link>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>



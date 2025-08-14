<script setup lang="ts">
// @ts-nocheck
import AppLayout from '@/layouts/AppLayout.vue';
import { Head } from '@inertiajs/vue3';
import type { BreadcrumbItem } from '@/types';

defineProps<{
    attendances: { data: { id: number; checked_in_at: string; method?: string; user?: { name: string } }[] };
}>();

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Dashboard', href: '/dashboard' },
    { title: 'Asistencias', href: '/attendance' },
];
</script>

<template>
    <Head title="Asistencias" />
    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="p-4 space-y-4">
            <div class="rounded-xl border divide-y">
                <div v-for="a in attendances.data" :key="a.id" class="p-3">
                    <div class="font-medium">{{ a.user?.name || 'Usuario' }}</div>
                    <div class="text-sm text-muted-foreground">{{ a.checked_in_at }} • {{ a.method || '—' }}</div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>



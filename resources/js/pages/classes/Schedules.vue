<script setup lang="ts">
// @ts-nocheck
import AppLayout from '@/layouts/AppLayout.vue';
import { Head } from '@inertiajs/vue3';
import type { BreadcrumbItem } from '@/types';

defineProps<{
    class: { id: number; title: string; type?: { name: string } };
    schedules: { id: number; starts_at: string; ends_at: string; room?: string }[];
}>();

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Dashboard', href: '/dashboard' },
    { title: 'Clases', href: '/classes' },
    { title: 'Horarios', href: '#' },
];
</script>

<template>
    <Head :title="`Horarios - ${$props.class.title}`" />
    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="p-4 space-y-4">
            <h1 class="text-xl font-semibold">{{ $props.class.title }} • {{ $props.class.type?.name }}</h1>
            <div class="rounded-xl border divide-y">
                <div v-for="s in schedules" :key="s.id" class="p-3">
                    <div class="font-medium">{{ s.starts_at }} → {{ s.ends_at }}</div>
                    <div class="text-sm text-muted-foreground">Sala: {{ s.room || '—' }}</div>
                </div>
            </div>
        </div>
    </AppLayout>
    
</template>



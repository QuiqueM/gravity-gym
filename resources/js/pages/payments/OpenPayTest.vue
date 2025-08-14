<script setup lang="ts">
// @ts-nocheck
import AppLayout from '@/layouts/AppLayout.vue';
import { Head, router } from '@inertiajs/vue3';
import type { BreadcrumbItem } from '@/types';
import Button from '@/components/ui/button/Button.vue';

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Dashboard', href: '/dashboard' },
    { title: 'Pagos', href: '#' },
];

function simulateCharge() {
    router.post('/payments/openpay/charge', {
        user_id: 1,
        amount: 100,
        token_id: 'tokn_dummy',
    });
}
</script>

<template>
    <Head title="OpenPay" />
    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="p-4">
            <Button @click="simulateCharge">Simular cargo</Button>
        </div>
    </AppLayout>
</template>



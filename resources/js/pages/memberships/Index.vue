<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { Head, Link } from '@inertiajs/vue3';
import type { BreadcrumbItem } from '@/types';
import Button from '@/components/ui/button/Button.vue';
import type { MembershipItem, Plan } from '@/types/membership';
import Icon from '@/components/Icon.vue';
import { useDates } from '@/composables/useDates';

defineProps<{
  plans: Plan[];
  memberships: { data: MembershipItem[] };
}>();

const { formatDate } = useDates();

const breadcrumbs: BreadcrumbItem[] = [
  { title: 'Dashboard', href: '/dashboard' },
  { title: 'Membresías', href: '/memberships' },
];

</script>

<template>
  <Head title="Membresías" />
  <AppLayout :breadcrumbs="breadcrumbs">
    <div class="p-4 space-y-6">
      <div class="flex items-center justify-between">
        <h1 class="text-xl font-semibold">Planes</h1>
        <Link :href="route('memberships-plan.create')">
          <Button size="sm">Configurar</Button>
        </Link>
      </div>
      <div class="grid gap-4 md:grid-cols-3">
        <div v-for="plan in plans" :key="plan.id" class="rounded-xl border p-4">
          <div class="font-medium flex justify-between items-center">
            <span>{{ plan.name }}</span>
            <Link :href="route('memberships-plan.edit', plan.id)">
            <Icon name="Edit" />
            </Link>
          </div>
          <div class="text-sm text-muted-foreground">{{ plan.description }}</div>
          <div class="mt-2 text-sm">Duración: {{ plan.duration_days }} días</div>
          <div class="mt-1 text-sm">Precio: ${{ plan.price }}</div>
        </div>
      </div>

      <div>
        <h2 class="text-xl font-semibold mb-2">Membresías activas</h2>
        <div class="rounded-xl border divide-y">
          <div v-for="item in memberships.data" :key="item.id" class="flex items-center justify-between p-3">
            <div>
              <div class="font-medium">{{ item.user.name }} • {{ item.plan.name }}</div>
              <div class="text-sm text-muted-foreground">
                {{ formatDate(item.starts_at) }} → {{ formatDate(item.ends_at) }} • {{ item.status }}
              </div>
            </div>
            <Button variant="secondary" size="sm">Ver</Button>
          </div>
        </div>
      </div>
    </div>
  </AppLayout>
</template>

<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head } from '@inertiajs/vue3';
import type { Auth, AdminProps } from '@/types';
import { useRole } from '@/composables/useRole';
import DashboardAdmin from './Dashboard/DashboardAdmin.vue';
import DashboardMember from './Dashboard/DashboardMember.vue';
import DashboardCoach from './Dashboard/DashboardCoach.vue';

const props = defineProps<{
  auth: Auth;
  nextClass: any | null;
  stats: AdminProps | null
}>();

const { isAdmin, isMember, isCoach } = useRole(props.auth.user.roles);

const breadcrumbs: BreadcrumbItem[] = [
  {
    title: 'Dashboard',
    href: '/dashboard',
  },
];

</script>

<template>
  <Head title="Dashboard" />

  <AppLayout :breadcrumbs="breadcrumbs">
    <div class="flex h-full flex-1 flex-col gap-4 rounded-xl p-4 overflow-x-auto">
      <section>
        <h2 class="text-lg font-semibold">Bienvenido {{ auth.user.name }}</h2>
        <p class="text-sm text-muted-foreground">
          Aquí puedes gestionar la configuración de tu cuenta, ver tu actividad y más.
        </p>
      </section>
      <DashboardMember v-if="isMember" :next-class="nextClass" />
      <DashboardAdmin v-else-if="isAdmin" :stats="stats!" />
      <DashboardCoach v-else-if="isCoach" :next-class="nextClass" />
    </div>
  </AppLayout>
</template>

<script setup lang="ts">
import { computed } from 'vue';
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head } from '@inertiajs/vue3';
import PlaceholderPattern from '../components/PlaceholderPattern.vue';
import type { Auth } from '@/types';
import { useRole } from '@/composables/useRole';
import DashboardAdmin from './Dashboard/DashboardAdmin.vue';
import DashboardMember from './Dashboard/DashboardMember.vue';

const props = defineProps<{
  auth: Auth;
}>();

const { isAdmin } = useRole();

const breadcrumbs: BreadcrumbItem[] = [
  {
    title: 'Dashboard',
    href: '/dashboard',
  },
];

const handleDashboard = computed(() => {
  if (isAdmin(props.auth.user.roles)) {
    return DashboardAdmin;
  }
  return DashboardMember;
});
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
      <component :is="handleDashboard" />
      <div
        class="relative min-h-[100vh] flex-1 rounded-xl border border-sidebar-border/70 md:min-h-min dark:border-sidebar-border">
        <PlaceholderPattern />
      </div>
    </div>
  </AppLayout>
</template>

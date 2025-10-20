<script setup lang="ts">
import { computed } from 'vue';
import AppLayout from '@/layouts/AppLayout.vue';
import { Head, Link } from '@inertiajs/vue3';
import type { Auth, BreadcrumbItem } from '@/types';
import { Button } from '@/components/ui/button';
import { useRole } from '@/composables/useRole';
import type { TypeClass, Classes } from '@/types/Class';
import type { Pagination } from '@/types';

const props = defineProps<{
  types: TypeClass[];
  classes: Pagination<Classes>;
  auth: Auth
}>();

const { isAdmin, isCoach } = useRole(props.auth.user.roles);

const permissions = computed(() => {
  return {
    canCreateClass: isAdmin.value || isCoach.value,
    canCreateCategory: isAdmin.value,
    canSeeCategories: isAdmin.value || isCoach.value,
    canAssignSchedules: isAdmin.value || isCoach.value,
  };
});

const breadcrumbs: BreadcrumbItem[] = [
  { title: 'Dashboard', href: '/dashboard' },
  { title: 'Clases', href: '/classes' },
];
</script>

<template>
  <Head title="Clases" />
  <AppLayout :breadcrumbs="breadcrumbs">
    <div class="p-4 space-y-6">
      <div v-if="permissions.canSeeCategories" class="space-y-4">
        <div class="flex justify-between items-center">
          <h2 class="text-xl font-semibold">Categorias</h2>
          <Link v-if="permissions.canCreateCategory" :href="route('classes.type.create')">
          <Button size="sm">Añadir</Button>
          </Link>
        </div>
        <div class="grid gap-4 md:grid-cols-3">
          <div v-for="type in types" :key="type.id" class="rounded-xl border p-4">
            <div class="font-medium">{{ type.name }}</div>
          </div>
        </div>
      </div>

      <div class="space-y-4">
        <div class="flex justify-between items-center ">
          <h2 class="text-xl font-semibold">Clases</h2>
          <Link v-if="permissions.canCreateClass" :href="route('classes.create')">
          <Button size="sm">Nueva clase</Button>
          </Link>
        </div>
        <div class="rounded-xl border divide-y">
          <div v-for="item in classes.data" :key="item.id" class="flex flex-col md:flex-row md:items-center md:justify-between p-3">
            <div>
              <div class="font-medium">{{ item.title }}</div>
              <div class="text-sm text-muted-foreground">{{ item.type?.name }}</div>
            </div>
            <div class="flex flex-row justify-stretch gap-2">
              <Link v-if="permissions.canAssignSchedules" :href="`/classes/${item.id}/schedules/create`"
                class="text-primary">Asignar horarios</Link>
              <span class="text-gray-500">|</span>
              <Link :href="`/classes/${item.id}/schedules`" class="text-primary">Ver horarios</Link>
            </div>
          </div>
        </div>
      </div>
    </div>
  </AppLayout>
</template>

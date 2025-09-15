<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { Head, Link } from '@inertiajs/vue3';
import type { BreadcrumbItem } from '@/types';
import { Button } from '@/components/ui/button';

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
      <div>
        <div class="flex justify-between items-center mb-2">
          <h2 class="text-xl font-semibold">Categorias</h2>
          <Link :href="route('classes.type.create')">
            <Button size="sm">Nueva categoria</Button>
          </Link>
        </div>
        <div class="grid gap-4 md:grid-cols-3">
          <div v-for="type in types" :key="type.id" class="rounded-xl border p-4">
            <div class="font-medium">{{ type.name }}</div>
          </div>
        </div>
      </div>

      <div>
         <div class="flex justify-between items-center mb-2">
          <h2 class="text-xl font-semibold">Listado</h2>
          <Link :href="route('classes.create')">
            <Button size="sm">Nueva clase</Button>
          </Link>
        </div>
        <div class="rounded-xl border divide-y">
          <div v-for="item in classes.data" :key="item.id" class="flex items-center justify-between p-3">
            <div>
              <div class="font-medium">{{ item.title }}</div>
              <div class="text-sm text-muted-foreground">{{ item.type?.name }}</div>
            </div>
            <div class="flex gap-x-2">
              <Link :href="`/classes/${item.id}/schedules/create`" class="text-primary">Asignar horarios</Link>
              <span class="text-gray-500">|</span>
              <Link :href="`/classes/${item.id}/schedules`" class="text-primary">Ver horarios</Link>
            </div>
          </div>
        </div>
      </div>
    </div>
  </AppLayout>
</template>

<script setup lang="ts">
import CardAdmin from '@/components/CardAdmin/CardAdmin.vue';
import { Users, IdCard } from 'lucide-vue-next';
import type { AdminProps } from '@/types';
import { Button } from '@/components/ui/button';
import { Link } from '@inertiajs/vue3';

defineProps<{
  stats: AdminProps;
}>();

</script>
<template>
  <div class="grid auto-rows-min gap-4 md:grid-cols-3">
    <div>
      <CardAdmin title="Miembros Registrados" :icon="Users">
        <template #content>
          <p class="text-2xl font-bold"> {{ stats.total_members }}</p>
          <span class="text-sm text-muted-foreground">{{ stats.current_month_members }} nuevos este mes</span>
        </template>
        <template #footer>
          <div class="w-full flex">
            <Link :href="route('admin.users.index')" class="w-full">
              <Button variant="default" class="w-full">Ver todos</Button>
            </Link>
          </div>
        </template>
      </CardAdmin>
    </div>
    <div>
      <CardAdmin title="Membresías Activas" :icon="IdCard">
        <template #content>
          <p class="text-2xl font-bold"> {{ stats.active_memberships }}</p>
          <span class="text-sm text-muted-foreground">{{ stats.expiring_soon_memberships }} por vencer</span>
        </template>
        <template #footer>
          <div class="w-full flex">
            <Link :href="route('memberships.index')" class="w-full">
              <Button variant="default" class="w-full">Ver todas</Button>
            </Link>
          </div>
        </template>
      </CardAdmin>
    </div>
  </div>
</template>
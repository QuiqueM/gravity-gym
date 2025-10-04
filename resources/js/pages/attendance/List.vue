<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { Head, router, Link } from '@inertiajs/vue3';
import type { BreadcrumbItem, User } from '@/types';
import { Button } from '@/components/ui/button';
import type { ScheduleWithClass } from '@/types/Schedule';
import Icon from '@/components/Icon.vue';


const props = defineProps<{
  schedule: ScheduleWithClass,
  attendances: {
    attended: boolean,
    user: User
  }[],
}>();

const breadcrumbs: BreadcrumbItem[] = [
  { title: 'Dashboard', href: '/dashboard' },
  { title: 'Asistencias', href: '/attendance' },
  { title: 'Listado', href: '#' },
];

const markAttendance = (userId: number) => {
  try {
    router.post(route('attendance.store'), 
      { 
        user_id: userId, 
        class_schedule_id: props.schedule.id,
        method: 'app'
      }, 
      { preserveState: true, only: ['attendances'] }
    );
  }catch (error) {
    console.error('Error al marcar la asistencia:', error);
  }
};


</script>

<template>
  <Head title="Listado de asistencia" />
  <AppLayout :breadcrumbs="breadcrumbs">
    <div class="p-4 space-y-4 grid">
      <h2 class="text-xl font-semibold">Listado de asistencia</h2>
      <div class="rounded-xl border divide-y">
        <div v-for="({user, attended}, index) in attendances" :key="index" class="p-3 flex justify-between items-center">
            <span class="font-medium"> {{ user.name }} </span>
          <div v-if="!attended" class="flex gap-2">
            <Button variant="destructive" > <Icon name="circleX" /> </Button>
            <Button @click="markAttendance(user.id)"> <Icon name="check" /> </Button>
          </div>
          <div v-else>
            <Icon name="check" />
          </div>
        </div>
      </div>
      <Link :href="route('attendance.index')">
        <Button variant="secondary"> Atras </Button>
      </Link>
    </div>
  </AppLayout>
</template>

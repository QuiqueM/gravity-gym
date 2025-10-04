<script setup lang="ts">
import { ref, watchEffect } from 'vue';
import AppLayout from '@/layouts/AppLayout.vue';
import { Head, router } from '@inertiajs/vue3';
import type { BreadcrumbItem } from '@/types';
import { useDates } from '@/composables/useDates';
import { Input } from '@/components/ui/input';
import { Button } from '@/components/ui/button';

const { now, onlyHoursAndMinutes, customParseFormat } = useDates();

defineProps<{
  schedules: any
}>();

const breadcrumbs: BreadcrumbItem[] = [
  { title: 'Dashboard', href: '/dashboard' },
  { title: 'Asistencias', href: '/attendance' },
];

const selectedDate = ref(now().format('YYYY-MM-DD'));

watchEffect(() => {
  router.get(route('attendance.index'), { date: selectedDate.value }, { preserveState: true, only: ['schedules'] });
});

const handleAttendance = (scheduleId: number) => {
  router.get(route('attendance.show', scheduleId));
};

</script>

<template>
  <Head title="Asistencias" />
  <AppLayout :breadcrumbs="breadcrumbs">
    <div class="p-4 space-y-4 grid">
      <div class="flex justify-between items-center">
        <h2 class="text-xl font-semibold">Horarios para: {{ customParseFormat(selectedDate, 'DD [de] MMMM') }} </h2>
        <Input id="starts_at" type="date" class="w-44" v-model="selectedDate"/>
      </div>
      <div class="rounded-xl border divide-y">
        <div v-for="({schedule, attendances}, index) in schedules" :key="index" class="p-3 flex justify-between items-center">
          <div class="grid">
            <span class="font-medium">{{ onlyHoursAndMinutes(schedule.starts_at) }} → {{ onlyHoursAndMinutes(schedule.ends_at) }}</span>
            <p class="text-sm text-muted-foreground flex gap-2">
              <span>{{ schedule.class.title }}</span> • <span> {{ attendances }} inscrito(s)</span>
            </p>
          </div>
          <Button @click="handleAttendance(schedule.id)"> Asistencias </Button>
        </div>
        <div v-if="schedules.length === 0" class="p-3 text-sm text-muted-foreground flex justify-center">
          <span>No hay horarios disponibles para hoy.</span>
        </div>
      </div>
    </div>
  </AppLayout>
</template>

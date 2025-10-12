<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { Head, router } from '@inertiajs/vue3';
import type { BreadcrumbItem } from '@/types';
import { useDates } from '@/composables/useDates';
import { Button } from '@/components/ui/button';
import { toast } from 'vue-sonner'
import DialogConfirm from '@/components/DialogConfirm/DialogConfirm.vue';
import { ref } from 'vue';
import type { Registration } from '@/types/Class';

defineProps<{
  registrations: Registration[];
}>();

const { formatDateTime, isMoreThanOneHour } = useDates();

const breadcrumbs: BreadcrumbItem[] = [
  { title: 'Dashboard', href: '/dashboard' },
  { title: 'Proximas Asistencias', href: '#' },
];

const toggleConfirmationModal = ref(false);
const attendanceSelected = ref<Registration | null>(null);

const cancelAttendance = () => {
  try {
    router.delete(route('attendance.cancel', attendanceSelected.value?.id), {
      only: ['registrations'], 
      preserveState: true, 
      onSuccess: () => {
        toast.success('Asistencia cancelada exitosamente');
        attendanceSelected.value = null;
        toggleConfirmationModal.value = false;
      },
      onError: (error) => {
        toast.error(error.error);
      }
    });
  } catch (error) {
    toast.error('Ocurrió un error al cancelar la asistencia');
    console.error(error);
  }
};

const selectAttendance = (attendance: Registration) => {
  attendanceSelected.value = attendance;
  toggleConfirmationModal.value = true;
}


</script>

<template>

  <Head title="Proximas Asistencias" />
  <AppLayout :breadcrumbs="breadcrumbs">
    <div class="p-4 space-y-4">
      <h1 class="text-xl font-semibold">Proximas Asistencias</h1>
      <div class="rounded-xl border divide-y">
        <div v-for="registration in registrations" :key="registration.id" class="p-3 flex justify-between items-center">
          <div class="flex flex-col">
            <span class="font-medium"> {{ registration.class_schedule.class
              .title }} </span>
            <span class="text-sm text-muted-foreground">Lugar: {{ registration.class_schedule.room }}</span>
            <span class="text-sm text-muted-foreground">Fecha: {{ formatDateTime(registration.class_schedule.starts_at)
              }}</span>
            <span class="text-sm text-muted-foreground">Coach: {{ registration.class_schedule.class.instructor.name
              }}</span>
          </div>
          <div class="flex gap-2 items-center" v-if="isMoreThanOneHour(registration.class_schedule.starts_at)">
            <Button variant="destructive" size="sm" @click="selectAttendance(registration)">Cancelar</Button>
          </div>
        </div>
      </div>
    </div>

    <!-- Confirmation Delete -->
    <DialogConfirm v-model="toggleConfirmationModal" @confirm="cancelAttendance">
      <template #title>¿Estás seguro de cancelar la asistencia?</template>
      <template #description>
        Para la clase {{ attendanceSelected?.class_schedule.class.title }} el día {{ formatDateTime(attendanceSelected?.class_schedule.starts_at || '') }}.
      </template>
    </DialogConfirm>
  </AppLayout>

</template>

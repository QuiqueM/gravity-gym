<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { Head, router } from '@inertiajs/vue3';
import type { Auth, BreadcrumbItem } from '@/types';
import { useDates } from '@/composables/useDates';
import { Button } from '@/components/ui/button';
import Icon from '@/components/Icon.vue';
import { toast } from 'vue-sonner'
import { useRole } from '@/composables/useRole';
import DialogConfirm from '@/components/DialogConfirm/DialogConfirm.vue';
import { ref } from 'vue';

const props = defineProps<{
  classSelected: { id: number; title: string; type?: { name: string } };
  schedules: { id: number; starts_at: string; ends_at: string; room?: string }[];
  auth: Auth
}>();

const { formatDateTime } = useDates();
const { isAdmin, isMember } = useRole(props.auth.user.roles);

const breadcrumbs: BreadcrumbItem[] = [
  { title: 'Dashboard', href: '/dashboard' },
  { title: 'Clases', href: '/classes' },
  { title: 'Horarios', href: '#' },
];
const toggleConfirmationModal = ref(false);
const toggleAttendModal = ref(false);
const scheduleSelected = ref<{ id: number; starts_at: string; ends_at: string; room?: string } | null>(null);

const selectSchedule = (schedule: { id: number; starts_at: string; ends_at: string; room?: string }, action: string) => {
  const handleDialogActions: Record<string, () => void> = {
    delete: () => {
      toggleConfirmationModal.value = true;
    },
    attend: () => {
      toggleAttendModal.value = true;
    },
  };
  scheduleSelected.value = schedule;
  handleDialogActions[action]?.();
};

const destroy = () => {
  try {
    if (scheduleSelected.value) {
      // Aquí iría la lógica para eliminar el horario, por ejemplo una llamada a la API
      toast.success('Horario eliminado exitosamente');
      scheduleSelected.value = null;
      toggleConfirmationModal.value = false;
    }
  } catch (error) {
    toast.error('Ocurrió un error al eliminar el horario');
    console.error(error);
  }
};

const attend = () => {
  try {
    if (scheduleSelected.value) {
      router.post(route('classes.schedules.attend', scheduleSelected.value.id),{},{
        onSuccess: () => {
          scheduleSelected.value = null;
          toggleAttendModal.value = false;
          toast.success('Asistencia confirmada exitosamente');
        },
        onError: (error) => {
          toast.error(error.error);
        }
      });
    }
  } catch (error) {
    toast.error('Ocurrió un error al confirmar la asistencia');
    console.error(error);
  }
};
</script>

<template>

  <Head :title="`Horarios - ${props.classSelected.title}`" />
  <AppLayout :breadcrumbs="breadcrumbs">
    <div class="p-4 space-y-4">
      <h1 class="text-xl font-semibold">{{ props.classSelected.title }} • {{ props.classSelected.type?.name }}</h1>
      <div class="rounded-xl border divide-y">
        <div v-for="s in schedules" :key="s.id" class="p-3 flex justify-between items-center">
          <div class="flex flex-col">
            <span class="font-medium">{{ formatDateTime(s.starts_at) }} → {{ formatDateTime(s.ends_at) }}</span>
            <span class="text-sm text-muted-foreground">Lugar: {{ s.room || '—' }}</span>
          </div>
          <div class="flex gap-2 items-center">
            <Button v-if="isAdmin" variant="destructive" size="sm" @click="selectSchedule(s, 'delete')"><Icon name="Trash" /></Button>
            <Button v-if="isMember" size="sm" @click="selectSchedule(s, 'attend')">Asistir</Button>
          </div>
        </div>
      </div>
    </div>

    <!-- Confirmation Delete -->
    <DialogConfirm v-model="toggleConfirmationModal" @confirm="destroy">
      <template #title>¿Estás seguro de eliminar el horario?</template>
      <template #description>
        Esta acción no se puede deshacer. Esto eliminará permanentemente el horario.
      </template>
    </DialogConfirm>
    <!-- Confirmation Attend -->
    <DialogConfirm v-model="toggleAttendModal" @confirm="attend">
      <template #title>¿Estás seguro de asistir al horario?</template>
      <template #description>
        Esto confirmará tu asistencia al horario.
      </template>
    </DialogConfirm>
  </AppLayout>

</template>

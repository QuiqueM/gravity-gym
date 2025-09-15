<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { Head } from '@inertiajs/vue3';
import type { BreadcrumbItem } from '@/types';
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
}>();

const { formatDateTime } = useDates();
const { isAdmin } = useRole();

const breadcrumbs: BreadcrumbItem[] = [
  { title: 'Dashboard', href: '/dashboard' },
  { title: 'Clases', href: '/classes' },
  { title: 'Horarios', href: '#' },
];
const toggleConfirmationModal = ref(false);
const scheduleSelected = ref<{ id: number; starts_at: string; ends_at: string; room?: string } | null>(null);

const selectScheduleForDeletion = (s: { id: number; starts_at: string; ends_at: string; room?: string }) => {
  console.log('Seleccionar horario para eliminación:', s);
  scheduleSelected.value = s;
  toggleConfirmationModal.value = true;
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
          <div>
            <Button v-if="isAdmin" variant="destructive" size="sm" @click="selectScheduleForDeletion(s)"><Icon name="Trash" /></Button>
          </div>
        </div>
      </div>
    </div>

    <!-- Confirmation Dialog -->
    <DialogConfirm v-model="toggleConfirmationModal" @confirm="destroy">
      <template #title>¿Estás seguro de eliminar el horario?</template>
      <template #description>
        Esta acción no se puede deshacer. Esto eliminará permanentemente el horario.
      </template>
    </DialogConfirm>
  </AppLayout>

</template>

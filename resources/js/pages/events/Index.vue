<template>

  <Head title="Eventos" />
  <AppLayout :breadcrumbs="breadcrumbs">
    <div class="p-4 space-y-6">
      <div class="flex items-center justify-between">
        <h1 class="text-2xl font-bold mb-4">Eventos</h1>
        <Link :href="route('events.create')">
        <Button>Crear evento</Button>
        </Link>
      </div>
      <div class="rounded-xl border divide-y">
        <div v-for="event in events" :key="event.id" class="flex gap-2 flex-row justify-between p-3">
          <div class="flex gap-4">
            <div class="min-w-20 w-20 h-20 bg-gray-200 rounded-lg overflow-hidden flex items-center justify-center">
              <img :src="event.image" :alt="event.title" class="object-cover w-full h-full" v-if="event.image" />
            </div>
            <div class="">
              <span class="font-medium">{{ event.title }}</span>
              <span class="text-sm text-muted-foreground line-clamp-2">
                {{ event.description }}
              </span>
            </div>
          </div>
          <div class="flex items-center justify-center gap-x-2">
            <Link :href="route('events.edit', event.id)">
              <Button variant="secondary">
                <Icon name="edit" class="w-4 h-4" />
              </Button>
            </Link>
            <Button variant="destructive">
              <Icon name="trash" class="w-4 h-4" @click="selectEventForDeletion(event)" />
            </Button>
          </div>
        </div>
        <div v-if="events.length === 0" class="p-4 text-center text-muted-foreground">
          No hay eventos disponibles.
        </div>
      </div>
    </div>
    <DialogConfirm v-model="toggleConfirmationModal" @confirm="destroy">
      <template #title>
        Confirmar eliminación
      </template>
      <template #description>
        ¿Estás seguro de que deseas eliminar este evento?
      </template>
    </DialogConfirm>
  </AppLayout>
</template>

<script setup lang="ts">
import { ref } from 'vue';
import { Link, router, Head } from '@inertiajs/vue3';
import { defineProps } from 'vue';
import AppLayout from '@/layouts/AppLayout.vue';
import { Button } from "@/components/ui/button"
import type { Event } from '@/types/Event';
import type { BreadcrumbItem } from '@/types';
import DialogConfirm from '@/components/DialogConfirm/DialogConfirm.vue';
import { toast } from 'vue-sonner';
import Icon from '@/components/Icon.vue';

defineProps<{
  events: Event[];
}>();

const breadcrumbs: BreadcrumbItem[] = [
  { title: 'Inicio', href: '/dashboard' },
  { title: 'Eventos', href: '/events' },
];

const eventSelected = ref<Event | null>(null);
const toggleConfirmationModal = ref(false);

const selectEventForDeletion = (event: Event) => {
  eventSelected.value = event;
  toggleConfirmationModal.value = true;
}

function destroy() {
  if (eventSelected.value) {
    router.delete(route('events.destroy', eventSelected.value.id), {
      onSuccess: () => {
        eventSelected.value = null;
        toast.success('Evento eliminado correctamente');
        router.reload({ only: ['events'] });
      }
    });
  }
}
</script>

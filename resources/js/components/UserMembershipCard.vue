<script setup lang="ts">
import { useDates } from '@/composables/useDates';
import { Button } from './ui/button';
import { useRole } from '@/composables/useRole';
import { usePage } from '@inertiajs/vue3'
const user = usePage().props.auth.user

const { isAdmin } = useRole(user.roles)
interface Props {
  membership: any;
}
defineProps<Props>();
const emits = defineEmits(['assignMembership']);
const { formatDate } = useDates();

const onAssignMembership = () => {
  emits('assignMembership');
}
</script>

<template>
  <section class="rounded-xl border p-4 shadow">
    <h3 class="font-semibold text-lg mb-2">Membresía</h3>
    <div v-if="membership">
      <div class="flex justify-between">
        <span>Plan:</span>
        <span class="text-muted-foreground">{{ membership.plan.name }}</span>
      </div>
      <div class="flex justify-between">
        <span>Estado:</span>
        <span class="text-muted-foreground">{{ membership.status }}</span>
      </div>
      <div class="flex justify-between">
        <span>Vigencia:</span>
        <span class="text-muted-foreground">{{ formatDate(membership.starts_at) }} - {{ formatDate(membership.ends_at) }}</span>
      </div>
    </div>
    <div v-else class="flex flex-col gap-4">
      <span class="text-muted-foreground"> Sin membresía activa</span>
      <Button v-if="isAdmin" @click="onAssignMembership">Asignar membresía</Button>
    </div>
  </section>
</template>

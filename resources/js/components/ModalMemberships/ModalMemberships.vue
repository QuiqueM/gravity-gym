<script setup lang="ts">
import {
  Sheet,
  SheetContent,
  SheetHeader,
  SheetTitle,
} from '@/components/ui/sheet'
import MembershipCard from '@/components/MembershipCard/MembershipCard.vue';
import type { Plan } from '@/types/membership';

defineProps<{
  memberships: Plan[];
}>();
const emits = defineEmits(['planSelected']);

const open = defineModel<boolean>('modelValue', { default: false });

const selectPlan = (plan: Plan) => {
  emits('planSelected', plan);
}
</script>

<template>
  <Sheet v-model:open="open">
    <SheetContent>
      <SheetHeader>
        <SheetTitle>Selecciona una Membresía</SheetTitle>
      </SheetHeader>
      <div class="grid p-4 gap-3 overflow-y-auto">
        <MembershipCard 
          v-for="plan in memberships" 
          :key="plan.id"
          :title="plan.name"
          :features="plan.features"
          :frequency="plan.duration_days"
          :price="plan.price"
          @select="selectPlan(plan)" />
      </div>
    </SheetContent>
  </Sheet>
</template>
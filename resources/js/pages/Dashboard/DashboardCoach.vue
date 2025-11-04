<script setup lang="ts">
import { computed } from 'vue';
import { useDates } from '@/composables/useDates';
import { Button } from '@/components/ui/button';
const props = defineProps<{
  nextClass: any | null;
}>();

const { formatDateTime } = useDates();

const hasNextClass = computed(() => {
  return props.nextClass !== null;
});
</script>
<template>
  <div class="grid auto-rows-min gap-4 md:grid-cols-3">
    <div class="flex flex-1 flex-col gap-4 rounded-xl  p-6 bg-gradient-to-br from-[#2785bd] to-[#174a6c]">
      <div class="flex flex-col gap-1">
        <h1 class="text-white text-base font-bold leading-tight italic">Tu proxima Clase</h1>
        <div v-if="hasNextClass">
          <p class="flex items-baseline gap-1 text-white">
            <span class="text-white text-4xl font-black leading-tight tracking-[-0.033em]">{{ nextClass.class.title
              }}</span>
          </p>
          <span class="text-white text-base font-bold leading-tight">{{ formatDateTime(nextClass.starts_at) }}</span>

        </div>
        <div v-else class=" flex flex-col gap-3 justify-center mt-3">
          <span class="text-white text-base font-bold leading-tight">Aún no tienes clases</span>
          <Button variant="secondary">Registra una clase</Button>
        </div>
      </div>
    </div>
  </div>
</template>
<script setup lang="ts">
import {
  Dialog,
  DialogContent,
  DialogFooter,
  DialogHeader,
  DialogTitle,
} from "@/components/ui/dialog"

defineProps<{
  title: string;
  imgUrl: string;
  description: string | null;
}>();

const open = defineModel({ default: false });

</script>

<template>
  <Dialog v-model:open="open">
    <DialogContent class="sm:max-w-xl md:max-w-2xl rounded-xl">
      <DialogHeader>
        <DialogTitle class="text-white text-2xl font-semibold">{{ title }}</DialogTitle>
      </DialogHeader>

      <div class="flex flex-col gap-4 pt-2">
        <div class="relative w-full rounded-lg overflow-hidden">
          <img :src="imgUrl" :alt="title" class="w-full h-auto rounded-lg" />
        </div>
        <p class="text-muted-foreground text-sm">
          {{ description }}
        </p>
      </div>

      <DialogFooter class="mt-4">
        <slot name="footer"></slot>
      </DialogFooter>
    </DialogContent>
  </Dialog>
</template>
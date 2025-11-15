<script setup lang="ts">
import {
  Dialog,
  DialogContent,
  DialogFooter,
  DialogHeader,
  DialogTitle,
} from "@/components/ui/dialog"
import type { CommentWithUser } from "@/types/Comments";
import CommentUser from '@/components/CommentUser/CommentUser.vue';
import { Button } from '@/components/ui/button';

defineProps<{
  comments: CommentWithUser[],
}>();

const open = defineModel({ default: false });

</script>

<template>
  <Dialog v-model:open="open">
    <DialogContent class="sm:max-w-xl md:max-w-2xl rounded-xl">
      <DialogHeader>
        <DialogTitle class="text-white text-2xl font-semibold"> Comentarios </DialogTitle>
      </DialogHeader>

      <div class="flex flex-col gap-4 pt-2 overflow-y-auto max-h-[70vh]">
        <CommentUser v-for="(comment, index) in comments" :key="index" :comment="comment" />
      </div>

      <DialogFooter class="mt-4">
        <Button variant="secondary" @click="open = false">Cerrar</Button>
      </DialogFooter>
    </DialogContent>
  </Dialog>
</template>
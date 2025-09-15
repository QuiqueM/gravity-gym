<script setup lang="ts">
import {
  AlertDialog,
  AlertDialogAction,
  AlertDialogCancel,
  AlertDialogContent,
  AlertDialogDescription,
  AlertDialogFooter,
  AlertDialogHeader,
  AlertDialogTitle,
} from "@/components/ui/alert-dialog"

const open = defineModel<boolean>({ default: false });

const emits =defineEmits<{
  (e: 'confirm'): void
}>();

const confirm = () => {
  emits('confirm');
};
</script>

<template>
  <AlertDialog v-model:open="open"> 
    <AlertDialogContent>
      <AlertDialogHeader>
        <AlertDialogTitle> <slot name="title" /></AlertDialogTitle>
        <AlertDialogDescription>
          <slot name="description" />
        </AlertDialogDescription>
      </AlertDialogHeader>
      <AlertDialogFooter>
        <AlertDialogCancel>Cancelar</AlertDialogCancel>
        <AlertDialogAction @click="confirm">Continuar</AlertDialogAction>
      </AlertDialogFooter>
    </AlertDialogContent>
  </AlertDialog>
</template>
<script lang="ts" setup>
import { Alert, AlertDescription, AlertTitle } from '@/components/ui/alert'

defineProps<{
  title: string;
  description: string;
  type: 'default' | 'destructive';
}>();
</script>

<template>
  <Alert :variant="type">
    <AlertTitle> {{ title }} </AlertTitle>
    <AlertDescription>
      {{ description }}
    </AlertDescription>
  </Alert>
</template>
<script lang="ts" setup>
import {
  Card,
  CardContent,
  CardFooter,
  CardHeader,
  CardTitle,
  CardAction
} from '@/components/ui/card'
import type { Component } from 'vue'

defineProps<{
  title: string;
  icon?: Component;
}>()
</script>
<template>
  <Card class="gap-2">
    <CardHeader>
      <CardTitle>{{ title }}</CardTitle>
      <CardAction>
        <component v-if="icon" :is="icon" class="size-5 md:size-8" />
      </CardAction>
    </CardHeader>
    <CardContent>
      <slot name="content"></slot>
    </CardContent>
    <CardFooter>
      <slot name="footer"></slot>
    </CardFooter>
  </Card>
</template>
<script setup lang="ts">
import PlaceholderPattern from '@/components/PlaceholderPattern.vue';
</script>
<template>
  <div class="grid auto-rows-min gap-4 md:grid-cols-3">
    <div
      class="relative aspect-video overflow-hidden rounded-xl border border-sidebar-border/70 dark:border-sidebar-border">
      <PlaceholderPattern />
    </div>
    <div
      class="relative aspect-video overflow-hidden rounded-xl border border-sidebar-border/70 dark:border-sidebar-border">
      <PlaceholderPattern />
    </div>
    <div
      class="relative aspect-video overflow-hidden rounded-xl border border-sidebar-border/70 dark:border-sidebar-border">
      <PlaceholderPattern />
    </div>
  </div>
</template>
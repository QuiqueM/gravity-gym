<script setup lang="ts">
import MercadoPagoMembershipModal from '@/components/MercadoPagoMembershipModal.vue';
import PlaceholderPattern from '@/components/PlaceholderPattern.vue';
import { ref } from 'vue';
const showMembershipModal = ref(true);
const selectedPlan = ref(null);
const selectedAmount = ref(0);
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
  <MercadoPagoMembershipModal
    :open="showMembershipModal"
    :plan="selectedPlan"
    :amount="selectedAmount"
    @close="showMembershipModal = false"
  />
</template>
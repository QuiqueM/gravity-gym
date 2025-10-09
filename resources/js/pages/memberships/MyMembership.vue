<script lang="ts" setup>
import { useDates } from '@/composables/useDates';
import AppLayout from '@/layouts/AppLayout.vue';
import type { BreadcrumbItem } from '@/types';
import { Membership, Plan } from '@/types/membership';
import { Head } from '@inertiajs/vue3';
import MessageAlert from '@/components/Alert/MessageAlert.vue';
import { computed, ref } from 'vue';
import { Button } from '@/components/ui/button';
import ModalMemberships from '@/components/ModalMemberships/ModalMemberships.vue';
import MercadoPagoMembershipModal from '@/components/MercadoPagoMembershipModal.vue';


const props = defineProps<{
  membership: Membership;
  plans: Plan[];
}>();

const showMemberships = ref(false);
const paymentModal = ref(false);
const selectedPlan = ref<Plan | null>(null);

const isMembershipExpired = computed(() => {
  return props.membership.status === 'expired';
});

const { formatDate } = useDates();

const breadcrumbs: BreadcrumbItem[] = [
  { title: 'Dashboard', href: '/dashboard' },
  { title: 'Tu Membresía', href: '#' },
];

const onPlanSelected = (plan: Plan) => {
  showMemberships.value = false;
  selectedPlan.value = plan;
  paymentModal.value = true;
}
</script>
<template>

  <Head title="Tu Membresía" />
  <AppLayout :breadcrumbs="breadcrumbs">
    <div class="flex flex-1 justify-center px-5 py-5 md:px-40">
      <div class="layout-content-container flex max-w-[960px] flex-1 flex-col">
        <div class="flex flex-wrap justify-between gap-3 p-4">
          <p class="tracking-light min-w-72 text-[32px] leading-tight font-bold text-white">Detalles de la membresía</p>
        </div>
        <h3 class="px-4 pt-4 pb-2 text-lg leading-tight font-bold tracking-[-0.015em] text-white">Membresía Actual</h3>
        <div class="p-4">
          <div class="flex items-stretch justify-between gap-4 rounded-lg">
            <div class="flex flex-[2_2_0px] flex-col gap-1">
              <p class="text-base leading-tight font-bold text-white">{{ membership.plan.name }}</p>
              <p class="text-sm leading-normal font-normal text-muted-foreground">{{ membership.plan.description }}</p>
              <MessageAlert v-if="isMembershipExpired" title="Membresía Vencida" description="Tu membresía está vencida." type="destructive" />
            </div>
            <div class="aspect-video w-full flex-1 rounded-lg bg-cover bg-center bg-no-repeat" 
              style="background-image: url('https://lh3.googleusercontent.com/aida-public/AB6AXuAIeM_ufZgsO6wZ2S7LUrFXhNSGpYxBa2chHSv4ZfJxY1jvsUQDWv0aNKRHuEIJs4XF8FmPOR3xWPxyFBdFG5HmOTNFl7PXLu-vCchQnVyJNdJpXq2omEgtv2WeVPX6DBGPh65uLIrQYDL_RGP9rCVinGB-dVwmhjy5KL5wNNANifwl6cGHlBHuV0TObILxauIv93dAfZNcVbe98E9XMxyUt-WWv3L21sc9foaBGRumaYws2zfoCQ33Hn-s3MkqLf6VRN7VWnt3iyw');"
              >
            </div>
          </div>
        </div>
        <div class="grid grid-cols-[20%_1fr] gap-x-6 p-4">
          <div class="col-span-2 grid grid-cols-subgrid border-t border-t-[#3a505f] py-5">
            <p class="text-sm leading-normal font-normal text-muted-foreground">Fecha de Inicio</p>
            <p class="text-sm leading-normal font-normal text-white">{{ formatDate(membership.starts_at) }}</p>
          </div>
          <div class="col-span-2 grid grid-cols-subgrid border-t border-t-[#3a505f] py-5">
            <p class="text-sm leading-normal font-normal text-[#9ab1c1]">Vence</p>
            <p class="text-sm leading-normal font-normal text-white">{{ formatDate(membership.ends_at) }}</p>
          </div>
          <div class="col-span-2 grid grid-cols-subgrid border-t border-t-[#3a505f] py-5">
            <p class="text-sm leading-normal font-normal text-[#9ab1c1]">Sesiones Restantes</p>
            <p class="text-sm leading-normal font-normal text-white">{{ membership.remaining_classes }}</p>
          </div>
        </div>
        <h3 class="px-4 pt-4 pb-2 text-lg leading-tight font-bold tracking-[-0.015em] text-white">Información de
          Facturación</h3>
        <div class="grid grid-cols-[20%_1fr] gap-x-6 p-4">
          <div class="col-span-2 grid grid-cols-subgrid border-t border-t-[#3a505f] py-5">
            <p class="text-sm leading-normal font-normal text-[#9ab1c1]">Método de Pago</p>
            <p class="text-sm leading-normal font-normal text-white">Tarjeta de Crédito terminando en 4567</p>
          </div>
          <div class="col-span-2 grid grid-cols-subgrid border-t border-t-[#3a505f] py-5">
            <p class="text-sm leading-normal font-normal text-[#9ab1c1]">Fecha del Próximo Pago</p>
            <p class="text-sm leading-normal font-normal text-white">15 de Febrero de 2024</p>
          </div>
        </div>
        <h3 class="px-4 pt-4 pb-2 text-lg leading-tight font-bold tracking-[-0.015em] text-white">Opciones de Membresía
        </h3>
        <div class="flex justify-stretch">
          <div class="flex flex-1 flex-wrap justify-start gap-3 px-4 py-3">
            <Button variant="default" @click="showMemberships = true">
              <span class="truncate">Renovar Membresía</span>
            </Button>
          </div>
        </div>
      </div>
    </div>
    <ModalMemberships v-model="showMemberships" :memberships="plans" @plan-selected="onPlanSelected" />
    
    <MercadoPagoMembershipModal
      :open="paymentModal"
      :plan="selectedPlan!"
      @close="paymentModal = false"
    />
  </AppLayout>
</template>

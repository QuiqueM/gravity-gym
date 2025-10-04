<script lang="ts" setup>
import { Head } from '@inertiajs/vue3';
import AppLayout from '@/layouts/AppLayout.vue';
import type { BreadcrumbItem } from '@/types';

const breadcrumbs: BreadcrumbItem[] = [
  { title: 'Dashboard', href: '/dashboard' },
  { title: 'Tu Membresía', href: '#' },
];
</script>
<template>
  <Head title="Tu Membresía" />
  <AppLayout :breadcrumbs="breadcrumbs">
    <div class="px-5 md:px-40 flex flex-1 justify-center py-5">
      <div class="layout-content-container flex flex-col max-w-[960px] flex-1">
        <div class="flex flex-wrap justify-between gap-3 p-4">
          <p class="text-white tracking-light text-[32px] font-bold leading-tight min-w-72">Detalles de la membresía</p>
        </div>
        <h3 class="text-white text-lg font-bold leading-tight tracking-[-0.015em] px-4 pb-2 pt-4">Membresía Actual</h3>
        <div class="p-4">
          <div class="flex items-stretch justify-between gap-4 rounded-lg">
            <div class="flex flex-col gap-1 flex-[2_2_0px]">
              <p class="text-white text-base font-bold leading-tight">Membresía Premium</p>
              <p class="text-muted-foreground text-sm font-normal leading-normal">Acceso a todas las instalaciones,
                clases ilimitadas y sesiones de entrenamiento personal.</p>
            </div>
            <div class="w-full bg-center bg-no-repeat aspect-video bg-cover rounded-lg flex-1"
              style='background-image: url("https://lh3.googleusercontent.com/aida-public/AB6AXuAIeM_ufZgsO6wZ2S7LUrFXhNSGpYxBa2chHSv4ZfJxY1jvsUQDWv0aNKRHuEIJs4XF8FmPOR3xWPxyFBdFG5HmOTNFl7PXLu-vCchQnVyJNdJpXq2omEgtv2WeVPX6DBGPh65uLIrQYDL_RGP9rCVinGB-dVwmhjy5KL5wNNANifwl6cGHlBHuV0TObILxauIv93dAfZNcVbe98E9XMxyUt-WWv3L21sc9foaBGRumaYws2zfoCQ33Hn-s3MkqLf6VRN7VWnt3iyw");'>
            </div>
          </div>
        </div>
        <div class="p-4 grid grid-cols-[20%_1fr] gap-x-6">
          <div class="col-span-2 grid grid-cols-subgrid border-t border-t-[#3a505f] py-5">
            <p class="text-muted-foreground text-sm font-normal leading-normal">Fecha de Inicio</p>
            <p class="text-white text-sm font-normal leading-normal">January 15, 2024</p>
          </div>
          <div class="col-span-2 grid grid-cols-subgrid border-t border-t-[#3a505f] py-5">
            <p class="text-[#9ab1c1] text-sm font-normal leading-normal">Vence</p>
            <p class="text-white text-sm font-normal leading-normal">January 14, 2025</p>
          </div>
          <div class="col-span-2 grid grid-cols-subgrid border-t border-t-[#3a505f] py-5">
            <p class="text-[#9ab1c1] text-sm font-normal leading-normal">Sesiones Restantes</p>
            <p class="text-white text-sm font-normal leading-normal">Ilimitadas</p>
          </div>
        </div>
        <h3 class="text-white text-lg font-bold leading-tight tracking-[-0.015em] px-4 pb-2 pt-4">Información de
          Facturación</h3>
        <div class="p-4 grid grid-cols-[20%_1fr] gap-x-6">
          <div class="col-span-2 grid grid-cols-subgrid border-t border-t-[#3a505f] py-5">
            <p class="text-[#9ab1c1] text-sm font-normal leading-normal">Método de Pago</p>
            <p class="text-white text-sm font-normal leading-normal">Tarjeta de Crédito terminando en 4567</p>
          </div>
          <div class="col-span-2 grid grid-cols-subgrid border-t border-t-[#3a505f] py-5">
            <p class="text-[#9ab1c1] text-sm font-normal leading-normal">Fecha del Próximo Pago</p>
            <p class="text-white text-sm font-normal leading-normal">15 de Febrero de 2024</p>
          </div>
        </div>
        <h3 class="text-white text-lg font-bold leading-tight tracking-[-0.015em] px-4 pb-2 pt-4">Opciones de Membresía
        </h3>
        <div class="flex justify-stretch">
          <div class="flex flex-1 gap-3 flex-wrap px-4 py-3 justify-start">
            <button
              class="flex min-w-[84px] max-w-[480px] cursor-pointer items-center justify-center overflow-hidden rounded-lg h-10 px-4 bg-[#205d85] text-white text-sm font-bold leading-normal tracking-[0.015em]">
              <span class="truncate">Actualizar Membresía</span>
            </button>
            <button
              class="flex min-w-[84px] max-w-[480px] cursor-pointer items-center justify-center overflow-hidden rounded-lg h-10 px-4 bg-[#293842] text-white text-sm font-bold leading-normal tracking-[0.015em]">
              <span class="truncate">Renovar Membresía</span>
            </button>
          </div>
        </div>
      </div>
    </div>
  </AppLayout>
</template>
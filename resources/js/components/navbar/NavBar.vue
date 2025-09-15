<script lang="ts" setup>
import { Link } from '@inertiajs/vue3';
import Icon from '@/components/Icon.vue';
</script>
<template>
  <header
    class="flex items-center justify-between whitespace-nowrap border-b border-solid border-b-[#392f28] px-2 md:px-10 py-3">
    <div class="flex items-center">
      <img src="/assets/logoNavbar.png" class="w-40" alt="logo grvity">
    </div>
    <div class="flex flex-1 justify-end gap-2 md:gap-6">
      <div class="hidden md:flex items-center gap-9">
        <a class="text-white text-sm font-medium leading-normal" href="#">Classes</a>
        <a class="text-white text-sm font-medium leading-normal" href="#">Coach</a>
        <a class="text-white text-sm font-medium leading-normal" href="#">Ubicación</a>
      </div>
      <Link
        class="flex max-w-[480px] cursor-pointer items-center justify-center overflow-hidden rounded-full h-10 px-2.5 md:px-4  text-white text-sm font-bold leading-normal tracking-[0.015em]"
        :href="route('register')">
        <span class="truncate hidden md:flex">Registrate</span>
        <Icon name="userPlus" class="h-5 w-5 md:hidden" />
      </Link>
      <Link
        class="flex  max-w-[480px] cursor-pointer items-center justify-center overflow-hidden rounded-full h-10 px-2.5 md:px-4 bg-primary text-white text-sm font-bold leading-normal tracking-[0.015em]"
        :href="route('login')">
        <span class="truncate hidden md:flex">Inicia Sesión</span>
        <Icon name="logIn" class="h-5 w-5 md:hidden" />
      </Link>
    </div>
  </header>
</template>
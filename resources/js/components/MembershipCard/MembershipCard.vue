<script lang="ts" setup>
import { computed } from 'vue';
interface Props {
  title: string;
  price: number|string;
  frequency: number;
  characteristics: string[];
}

const props = defineProps<Props>();
const emits = defineEmits(['select']);

const price = computed(() => {
  if (Number(props.price) === 0) {
    return 'Gratis';
  }
  return `${props.price}`;
});

const onClick = () => {
  emits('select');
}
</script>

<template>
  <div class="flex flex-1 flex-col gap-4 rounded-xl border border-solid border-primary bg-[#1c2227fa] p-6">
    <div class="flex flex-col gap-1">
      <h1 class="text-white text-base font-bold leading-tight">{{ title }}</h1>
      <p class="flex items-baseline gap-1 text-white">
        <span class="text-white text-4xl font-black leading-tight tracking-[-0.033em]">{{ price }}</span>
        <span class="text-white text-base font-bold leading-tight">/ {{ frequency }} días</span>
      </p>
    </div>
    <button
      class="flex min-w-[84px] max-w-[480px] cursor-pointer items-center justify-center overflow-hidden rounded-full h-10 px-4 bg-primary text-white text-sm font-bold leading-normal tracking-[0.015em]"
      @click="onClick"
    >
      <span class="truncate">Comprar</span>
    </button>
    <div class="flex flex-col gap-2">
      <div v-for="(characteristic, index) in characteristics" :key="index" class="text-[13px] font-normal leading-normal flex gap-3 text-white">
        <div class="text-white" data-icon="Check" data-size="20px" data-weight="regular">
          <svg xmlns="http://www.w3.org/2000/svg" width="20px" height="20px" fill="currentColor" viewBox="0 0 256 256">
            <path
              d="M229.66,77.66l-128,128a8,8,0,0,1-11.32,0l-56-56a8,8,0,0,1,11.32-11.32L96,188.69,218.34,66.34a8,8,0,0,1,11.32,11.32Z">
            </path>
          </svg>
        </div>
        {{ characteristic }}
      </div>  
    </div>
  </div>
</template>
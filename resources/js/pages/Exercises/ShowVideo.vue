<script setup lang="ts">
import { ref, watch } from 'vue';
import {
  Dialog,
  DialogContent,
  DialogHeader,
  DialogTitle,
} from "@/components/ui/dialog"

defineProps<{
  title: string;
  videoUrl: string;
  description: string | null;
}>();

const open = defineModel({ default: false });

const videoPlayer = ref<HTMLVideoElement | null>(null);
const hasVideoStarted = ref(false);

function onVideoPlay() {
  hasVideoStarted.value = true;
}

function onVideoEnd() {
  hasVideoStarted.value = false;
}

watch(open, (isOpen) => {
  if (!isOpen) {
    videoPlayer.value?.pause();
    videoPlayer.value?.load(); 
    hasVideoStarted.value = false;
  }
});
</script>

<template>
  <Dialog v-model:open="open">
    <DialogContent 
      class="sm:max-w-xl md:max-w-2xl rounded-xl"
    >
      <DialogHeader>
        <DialogTitle class="text-white text-2xl font-semibold">{{ title }}</DialogTitle>
      </DialogHeader>

      <div class="flex flex-col gap-4 pt-2">
        
        <div class="relative w-full rounded-lg overflow-hidden">
          
          <video
            v-if="videoUrl"
            ref="videoPlayer"       
            @play="onVideoPlay"
            @ended="onVideoEnd"
            controls
            class="w-full max-h-[40rem]" 
          >
            <source :src="videoUrl" type="video/mp4" />
            Your browser does not support the video tag.
          </video>
          
          <Transition name="fade">
            <div 
              v-if="!hasVideoStarted && description"
              class="absolute bottom-0 left-0 right-0 h-1/2 flex items-end p-4 bg-gradient-to-t from-black/70 to-transparent pointer-events-none"
            >
              <p class="text-white text-sm">
                {{ description }}
              </p>
            </div>
          </Transition>

        </div>
        </div>
    </DialogContent>
  </Dialog>
</template>

<style scoped>
.fade-enter-active,
.fade-leave-active {
  transition: opacity 0.5s ease; /* Duración y tipo de animación */
}

.fade-enter-from, /* Estado inicial al entrar */
.fade-leave-to {    /* Estado final al salir */
  opacity: 0;
}

.fade-enter-to,   /* Estado final al entrar */
.fade-leave-from { /* Estado inicial al salir */
  opacity: 1;
}
</style>
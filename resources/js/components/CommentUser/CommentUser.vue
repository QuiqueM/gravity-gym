<script setup lang="ts">
import { defineProps } from 'vue';
import type { CommentWithUser } from '@/types/Comments';
import { Avatar, AvatarFallback, AvatarImage } from '@/components/ui/avatar';
import { useInitials } from '@/composables/useInitials';
import { useDates } from '@/composables/useDates';

const { getInitials } = useInitials();
const { formatDistance } = useDates();

defineProps<{
  comment: CommentWithUser;
}>();
</script>
<template>
  <div class="flex flex-col gap-3 bg-[#181411]">
    <div class="flex items-center gap-3">
      <Avatar class="size-10 overflow-hidden rounded-full">
        <AvatarImage :src="comment.user.avatar!" :alt="comment.user.name" />
        <AvatarFallback class="rounded-lg text-black dark:text-white">
          <span class="text-md">{{ getInitials(comment.user.name) }}</span>
        </AvatarFallback>
      </Avatar>
      <div class="flex-1">
        <p class="text-white text-base font-medium leading-normal">{{ comment.user.name }}</p>
        <p class="text-[#b9a89d] text-sm font-normal leading-normal">{{ formatDistance(comment.created_at) }}</p>
      </div>
    </div>
    <div class="flex gap-0.5">
      <div v-for="index in 5" :key="index" :class="index <= comment.rating ? 'text-white' : 'text-[#776355]'" data-icon="Star" data-size="20px" :data-weight="index <= comment.rating ? 'fill' : 'regular'">
        <svg xmlns="http://www.w3.org/2000/svg" width="20px" height="20px" fill="currentColor" viewBox="0 0 256 256">
          <path
            d="M234.5,114.38l-45.1,39.36,13.51,58.6a16,16,0,0,1-23.84,17.34l-51.11-31-51,31a16,16,0,0,1-23.84-17.34L66.61,153.8,21.5,114.38a16,16,0,0,1,9.11-28.06l59.46-5.15,23.21-55.36a15.95,15.95,0,0,1,29.44,0h0L166,81.17l59.44,5.15a16,16,0,0,1,9.11,28.06Z">
          </path>
        </svg>
      </div>
    </div>
    <p class="text-white text-base font-normal leading-normal">
      {{ comment.comment }}
    </p>
  </div>
</template>
<script lang="ts" setup>
import { computed } from 'vue';
import { useForm } from '@inertiajs/vue3';
import { Avatar, AvatarFallback, AvatarImage } from '@/components/ui/avatar';
import { toast } from 'vue-sonner';
import { User } from '@/types';
import { useInitials } from '@/composables/useInitials';
import { Input } from "@/components/ui/input"
import { Button } from '@/components/ui/button';


const props = defineProps<{
  user: User
}>();

const emit = defineEmits<{
  (e: 'update'): void;
}>();

const { getInitials } = useInitials();


const formAvatar = useForm({
  avatar: null as File | null,
})

const showAvatar = computed(() => props.user.avatar && props.user.avatar !== '');


const submitAvatar = () => {
  formAvatar.post(route('profile.avatar.update'), {
    onSuccess: () => {
      toast.success('Avatar actualizado exitosamente');
      formAvatar.reset();
      emit('update');
    },
    onError: () => {
      toast.error('Ocurrió un error al actualizar el avatar');
    },
  });
};
</script>

<template>
  <div class="flex flex-col items-center gap-4">
    <Avatar class="size-24 overflow-hidden rounded-full">
      <AvatarImage v-if="showAvatar" :src="user.avatar!" :alt="user.name" />
      <AvatarFallback class="rounded-lg text-black dark:text-white">
        <span class="text-xl">{{ getInitials(user.name) }}</span>
      </AvatarFallback>
    </Avatar>
    <form @submit.prevent="submitAvatar" class="flex w-full max-w-sm items-center gap-1.5">
      <Input id="avatar" type="file" accept="image/*" @input="formAvatar.avatar = $event.target.files[0]" />
      <Button type="submit" size="sm" :disabled="formAvatar.processing">Actualizar</Button>
    </form>
  </div>
</template>
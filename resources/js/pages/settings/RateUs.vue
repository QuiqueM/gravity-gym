<script setup lang="ts">
import { Head, useForm } from '@inertiajs/vue3';
import HeadingSmall from '@/components/HeadingSmall.vue';
import { Button } from '@/components/ui/button';
import AppLayout from '@/layouts/AppLayout.vue';
import SettingsLayout from '@/layouts/settings/Layout.vue';
import { type BreadcrumbItem } from '@/types';
import { Textarea } from "@/components/ui/textarea"
import { toast } from 'vue-sonner';
import InputError from '@/components/InputError.vue';

interface Props {
  review: {
    id: number;
    rating: number;
    comment: string;
    created_at: string;
  } | null;
}

const props = defineProps<Props>();

const breadcrumbItems: BreadcrumbItem[] = [
  {
    title: 'Perfil',
    href: '/settings/profile',
  },
];


const reviewForm = useForm({
  rating: props.review ? props.review.rating : 0,
  comment: props.review ? props.review.comment : '',
});

const submitReview =  () => {
  if (props.review) {
    updateReview();
  } else {
    saveReview();
  }
};

const saveReview = () => {
  reviewForm.post(route('reviews.store'), {
    onSuccess: () => {
      toast.success('Reseña enviada con éxito');
    },
    onError: () => {
      toast.error('Error al enviar la reseña');
    },
  })
}

const updateReview = () => {
  reviewForm.put(route('reviews.update', props.review!.id), {
    onSuccess: () => {
      toast.success('Reseña actualizada con éxito');
    },
    onError: () => {
      toast.error('Error al actualizar la reseña');
    },
  });
}


</script>

<template>
  <AppLayout :breadcrumbs="breadcrumbItems">

    <Head title="Calificanos" />

    <SettingsLayout>
      <div class="flex flex-col space-y-6">
        <HeadingSmall title="Calificanos" description="Déjanos tu opinión" />
        <form @submit.prevent="submitReview" class="space-y-4 max-w-md">
          <div class="flex items-center gap-2">
            <span v-for="star in 5" :key="star" @click="reviewForm.rating = star" class="cursor-pointer">
              <svg :class="reviewForm.rating >= star ? 'text-yellow-400' : 'text-gray-300'" xmlns="http://www.w3.org/2000/svg" width="32" height="32" fill="currentColor" viewBox="0 0 24 24">
                <path d="M12 17.75l-6.172 3.245 1.179-6.881-5-4.868 6.9-1.001L12 2.25l3.093 6.995 6.9 1.001-5 4.868 1.179 6.881z"/>
              </svg>
            </span>
          </div>
          <Textarea v-model="reviewForm.comment" placeholder="Escribe tu comentario..." required />
          <InputError :message="reviewForm.errors.comment" />
          <Button v-if="!props.review" type="submit" :disabled="reviewForm.processing || reviewForm.rating === 0 || !reviewForm.comment">
            {{ reviewForm.processing ? 'Enviando...' : 'Enviar reseña' }}
          </Button>
          <Button v-else type="submit" :disabled="reviewForm.processing || reviewForm.rating === 0 || !reviewForm.comment">
            Actualizar reseña
          </Button>
        </form>
      </div>
    </SettingsLayout>
  </AppLayout>
</template>

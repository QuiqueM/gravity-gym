<script setup lang="ts">
import { Head, router, Link } from '@inertiajs/vue3';
import DeleteUser from '@/components/DeleteUser.vue';
import HeadingSmall from '@/components/HeadingSmall.vue';
import { Button } from '@/components/ui/button';
import AppLayout from '@/layouts/AppLayout.vue';
import SettingsLayout from '@/layouts/settings/Layout.vue';
import { type BreadcrumbItem, type User } from '@/types';
import { computed, ref } from 'vue';
import { useDates } from '@/composables/useDates';
import EditProfile from '@/components/EditProfile/EditProfile.vue';
import UpdateAvatar from '@/components/UpdateAvatar.vue';

interface Props {
  mustVerifyEmail: boolean;
  status?: string;
  auth: {
    user: User;
  }
}

const props = defineProps<Props>();
const { formatDate } = useDates();

const breadcrumbItems: BreadcrumbItem[] = [
  {
    title: 'Perfil',
    href: '/settings/profile',
  },
];

const showEditProfile = ref(false);


const attributesUser = computed(() => {
  return [
    { label: 'Nombre', value: props.auth.user.name },
    { label: 'Email', value: props.auth.user.email },
    { label: 'Teléfono', value: props.auth.user.phone || 'No especificado' },
    { label: 'Miembro desde', value: formatDate(props.auth.user.created_at) },
    { label: 'Última actualización', value: formatDate(props.auth.user.updated_at) },
  ]
})

const refreshUser = () => {
  router.reload({ only: ['auth.user'] });
}



</script>

<template>
  <AppLayout :breadcrumbs="breadcrumbItems">

    <Head title="Perfil de Usuario" />

    <SettingsLayout>
      <div class="flex flex-col space-y-6">
        <UpdateAvatar :user="auth.user" @update="refreshUser" />
        <HeadingSmall title="Información del perfil" description="Actualiza tu información" />
        <div v-if="mustVerifyEmail && !auth.user.email_verified_at" class="rounded-lg bg-yellow-50 p-4">
          <p class="-mt-4 text-sm text-muted-foreground">
            Tu dirección de correo electrónico no está verificada.
            <Link :href="route('verification.send')" method="post" as="button"
              class="text-foreground underline decoration-neutral-300 underline-offset-4 transition-colors duration-300 ease-out hover:decoration-current! dark:decoration-neutral-500">
            Haz clic aquí para volver a enviar el correo de verificación.
            </Link>
          </p>

          <div v-if="status === 'verification-link-sent'" class="mt-2 text-sm font-medium text-green-600">
            Se ha enviado un nuevo enlace de verificación a tu dirección de correo electrónico.
          </div>
        </div>
        <div class="grid grid-cols-[25%_1fr] gap-x-6">
          <div v-for="(user, index) in attributesUser" :key="index"
            class="col-span-2 grid grid-cols-subgrid border-t border-t-muted-foreground py-5">
            <p class="text-sm leading-normal font-normal text-muted-foreground">{{ user.label }}</p>
            <p class="text-sm leading-normal font-normal text-white">{{ user.value }}</p>
          </div>
        </div>
        <div class="w-full flex justify-end">
          <Button @click="showEditProfile = true">Editar</Button>
        </div>

      </div>

      <DeleteUser />
    </SettingsLayout>
    <EditProfile v-model="showEditProfile" :user="auth.user" @update="refreshUser" />
  </AppLayout>
</template>

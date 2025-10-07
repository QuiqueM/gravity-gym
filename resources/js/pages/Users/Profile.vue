<script setup lang="ts">
import { computed, ref } from 'vue';
import { Link, Head, useForm } from '@inertiajs/vue3';
import UserMembershipCard from '@/components/UserMembershipCard.vue';
import { useDates } from '@/composables/useDates';
import AppLayout from '@/layouts/AppLayout.vue';
import { Button } from '@/components/ui/button';
import { Label } from '@/components/ui/label';
import { Avatar, AvatarFallback, AvatarImage } from '@/components/ui/avatar';
import { useInitials } from '@/composables/useInitials';
import type { UserWhithMembership } from '@/types';
import type { Plan } from '@/types/membership';
import {
  Sheet,
  SheetClose,
  SheetContent,
  SheetDescription,
  SheetFooter,
  SheetHeader,
  SheetTitle,
} from "@/components/ui/sheet"
import {
  Select,
  SelectContent,
  SelectItem,
  SelectTrigger,
  SelectValue,
} from "@/components/ui/select"
import { toast } from 'vue-sonner';
import { Input } from "@/components/ui/input"

interface Props {
  user: UserWhithMembership;
  memberships_plan: Plan[];
}

const { formatDate } = useDates();
const { getInitials } = useInitials();

const props = defineProps<Props>();

const breadcrumbs = [
  { title: 'Dashboard', href: '/dashboard' },
  { title: 'Perfil', href: '#' },
];
const showSheet = ref(false);

const form = useForm({
  user_id: props.user.id,
  membership_plan_id: null,
})

const formAvatar = useForm({
  avatar: null as File | null,
})

const showAvatar = computed(() => props.user.avatar && props.user.avatar !== '');
const userIsAdmin = computed(() => {
  const rol = props.user.roles.find((r: any) => r.name === 'Admin');
  return rol ? true : false;
})
const onAssignMembership = () => {
  console.log('Asignar membresía');
  showSheet.value = true;
}

const submit = () => {
  form.post(route('memberships.store'), {
    onSuccess: () => {
      toast.success('Membresía asignada exitosamente');
      form.reset();
      route.reload({ only: ['user'] });
      showSheet.value = false;
    },
    onError: () => {
      toast.error('Ocurrió un error al crear la membresía');
    },
  });
};

const submitAvatar = () => {
  formAvatar.post(route('profile.avatar.update'), {
    onSuccess: () => {
      toast.success('Avatar actualizado exitosamente');
      formAvatar.reset();
      route.reload({ only: ['user'] });
    },
    onError: () => {
      toast.error('Ocurrió un error al actualizar el avatar');
    },
  });
};
</script>
<template>

  <Head title="Perfil de usuario" />
  <AppLayout :breadcrumbs="breadcrumbs">
    <div class="space-y-6 p-4">
      <div class="mb-6 flex items-center justify-between">
        <h1 class="text-2xl font-bold">Perfil de usuario</h1>
      </div>
      <div class="flex flex-col items-center">
        <Avatar class="size-24 overflow-hidden rounded-full">
          <AvatarImage v-if="showAvatar" :src="user.avatar!" :alt="user.name" />
          <AvatarFallback class="rounded-lg text-black dark:text-white">
            <span class="text-xl">{{ getInitials(user.name) }}</span>
          </AvatarFallback>
        </Avatar>
        <form @submit.prevent="submitAvatar" class="grid w-full max-w-sm items-center gap-1.5">
          <Label for="avatar">Cambiar imagen</Label>
          <Input id="avatar" type="file" @input="formAvatar.avatar = $event.target.files[0]" />
          <Button type="submit" size="sm" >Actualizar</Button>
        </form>
      </div>
      <div class="flex flex-col md:flex-row gap-6 justify-center">
        <!-- Card de información del usuario -->
        <section class="rounded-xl border p-4  shadow w-full md:w-1/2">
          <h3 class="font-semibold text-lg mb-2">Información personal</h3>
          <div class="flex justify-between">
            <span>Nombre:</span>
            <span class="text-muted-foreground">{{ user.name }}</span>
          </div>
          <div class="flex justify-between">
            <span>Correo:</span>
            <span class="text-muted-foreground">{{ user.email }}</span>
          </div>
          <div class="flex justify-between">
            <span>Telefono:</span>
            <span class="text-muted-foreground">{{ user.phone }}</span>
          </div>
          <div class="flex justify-between">
            <span>Rol:</span>
            <span class="text-muted-foreground">{{user.roles?.map((r: any) => r.name).join(', ')}}</span>
          </div>
          <div class="flex justify-between">
            <span>Registrado:</span>
            <span class="text-muted-foreground">{{ formatDate(user.created_at) }}</span>
          </div>
        </section>
        <!-- Card de membresía -->
        <UserMembershipCard v-if="!userIsAdmin" :membership="user.membership" class="w-full md:w-1/2"
          @assign-membership="onAssignMembership" />
      </div>
      <Link :href="route('admin.users.index')">
      <Button variant="outline" class="w-full md:w-48">Atras</Button>
      </Link>
    </div>

    <Sheet v-model:open="showSheet">
      <SheetContent side="right" class="w-[calc(100vw-5rem)] sm:w-96">
        <form method="POST" @submit.prevent="submit">
          <SheetHeader>
            <SheetTitle>Asignar membresía</SheetTitle>
            <SheetDescription>
              Aquí puedes asignar una nueva membresía al usuario.
            </SheetDescription>
          </SheetHeader>
          <div class="grid gap-4 p-4">
            <div class="flex flex-col gap-2">
              <Label for="role_id">Membresías</Label>
              <Select id="filter" v-model="form.membership_plan_id">
                <SelectTrigger class="w-full">
                  <SelectValue placeholder="Seleccionar" />
                </SelectTrigger>
                <SelectContent>
                  <SelectItem v-for="membership in memberships_plan" :key="membership.id" :value="membership.id"> {{
                    membership.name }} </SelectItem>
                </SelectContent>
              </Select>
            </div>
          </div>
          <SheetFooter>
            <SheetClose as-child>
              <Button type="submit">
                Guardar
              </Button>
            </SheetClose>
          </SheetFooter>
        </form>
      </SheetContent>
    </Sheet>
  </AppLayout>
</template>

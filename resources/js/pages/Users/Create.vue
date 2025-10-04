<script lang="ts" setup>
import AppLayout from '@/layouts/AppLayout.vue';
import { Head, useForm, Link } from '@inertiajs/vue3';
import type { BreadcrumbItem } from '@/types';
import type { Role } from '@/types/Role';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Button } from '@/components/ui/button';
import InputError from '@/components/InputError.vue';
import { LoaderCircle } from 'lucide-vue-next';
import {
  Select,
  SelectContent,
  SelectItem,
  SelectTrigger,
  SelectValue,
} from "@/components/ui/select"
import { toast } from 'vue-sonner'
defineProps<{
  roles: Role[];
}>();

const breadcrumbs: BreadcrumbItem[] = [
	{ title: 'Dashboard', href: '/dashboard' },
	{ title: 'Usuarios', href: '/users' },
];

const form = useForm({
  name: '',
  email: '',
  phone: '',
  role_id: 1,
  password: '',
  password_confirmation: '',
});

const submit = () => {
  form.post(route('admin.users.store'), {
    onSuccess: () => {
      toast.success('Usuario creado exitosamente');
      form.reset();
    },
    onError: () => {
      toast.error('Ocurrió un error al crear el usuario');
    },
  });
};
</script>

<template>
	<Head title="Crear usuario" />
	<AppLayout :breadcrumbs="breadcrumbs">
		<div class="p-4 space-y-6">
			<div class="flex items-center justify-between">
				<h1 class="text-xl font-semibold">Crear usuario</h1>
			</div> 
			
      <form method="POST" @submit.prevent="submit" class="flex flex-col gap-6">
      <div class="grid gap-6">
        <div class="grid gap-2">
          <Label for="name">Nombre</Label>
          <Input id="name" type="text" required autofocus :tabindex="1" autocomplete="name" v-model="form.name"
            placeholder="Nombre completo" />
          <InputError :message="form.errors.name" />
        </div>

        <div class="grid gap-2">
          <Label for="email">Email</Label>
          <Input id="email" type="email" required :tabindex="2" autocomplete="email" v-model="form.email"
            placeholder="email@example.com" />
          <InputError :message="form.errors.email" />
        </div>

        <div class="grid gap-2">
          <Label for="phone">Telefono</Label>
          <Input id="phone" type="tel" required :tabindex="2" autocomplete="tel" v-model="form.phone"
            placeholder="123-456-7890" />
          <InputError :message="form.errors.phone" />
        </div>

        <div class="flex flex-col gap-2">
          <Label for="role_id">Rol</Label>
          <Select id="filter" v-model="form.role_id">
            <SelectTrigger class="w-full">
              <SelectValue placeholder="Filtrar por" />
            </SelectTrigger>
            <SelectContent>
              <SelectItem v-for="role in roles" :key="role.id" :value="role.id"
                > {{ role.name }} </SelectItem>
            </SelectContent>
          </Select>
        </div>

        <div class="grid gap-2">
          <Label for="password">Contraseña</Label>
          <Input id="password" type="password" required :tabindex="3" autocomplete="new-password"
            v-model="form.password" placeholder="Contraseña" />
          <InputError :message="form.errors.password" />
        </div>

        <div class="grid gap-2">
          <Label for="password_confirmation">Confirmar contraseña</Label>
          <Input id="password_confirmation" type="password" required :tabindex="4" autocomplete="new-password"
            v-model="form.password_confirmation" placeholder="Confirmar contraseña" />
          <InputError :message="form.errors.password_confirmation" />
        </div>

        <div class="flex justify-between items-center">
          <Link :href="route('admin.users.index')">
            <Button variant="outline" class="w-full md:w-48">Cancelar</Button>
          </Link>
          <Button type="submit" class="w-full md:w-48" tabindex="5" :disabled="form.processing">
            <LoaderCircle v-if="form.processing" class="h-4 w-4 animate-spin" />
            Crear
          </Button>
        </div>
      </div>
    </form>
			
		</div>
	</AppLayout>
</template>
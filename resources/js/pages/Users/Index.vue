<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { Head, Link, router } from '@inertiajs/vue3';
import Button from '@/components/ui/button/Button.vue';
import Icon from '@/components/Icon.vue';
import DialogConfirm from '@/components/DialogConfirm/DialogConfirm.vue';
import type { User } from '@/types';
import { ref } from 'vue';
import { toast } from 'vue-sonner'

defineProps<{
	users: User[]
}>();

const toggleConfirmationModal = ref(false);
const userSelected = ref<User | null>(null);

const breadcrumbs = [
	{ title: 'Dashboard', href: '/dashboard' },
	{ title: 'Usuarios', href: '/users' },
];

const destroy = () => {
	try {
		if (userSelected.value) {
			router.delete(route('admin.users.destroy', userSelected.value.id), {only: ['users']});
			toast.success('Usuario eliminado exitosamente');
			userSelected.value = null;
			toggleConfirmationModal.value = false;
		}
	} catch (error) {
		toast.error('Ocurrió un error al eliminar el usuario');
		console.error(error);
	}
}

const selectUserForDeletion = (user: User) => {
		userSelected.value = user;
		toggleConfirmationModal.value = true;
};
</script>

<template>
	<Head title="Usuarios" />
	<AppLayout :breadcrumbs="breadcrumbs">
		<div class="p-4 space-y-6">
			<div class="flex items-center justify-between">
				<h1 class="text-xl font-semibold">Usuarios</h1>
				<Link :href="route('admin.users.create')">
					<Button size="sm">Nuevo usuario</Button>
				</Link>
			</div>
			<div class="rounded-xl border divide-y">
				<div
					v-for="user in users"
					:key="user.id"
					class="flex items-center justify-between p-3"
				>
					<div>
						<div class="font-medium">{{ user.name }} <span class="text-xs text-muted-foreground">({{ user.email }})</span></div>
						<div class="text-sm text-muted-foreground">Tel: {{ user.phone }}</div>
					</div>
					<div class="flex gap-2">
						<Link :href="route('admin.users.edit', user.id)">
							<Button variant="secondary" size="sm"><Icon name="Edit" /></Button>
						</Link>
						<Button variant="destructive" size="sm" @click="selectUserForDeletion(user)"><Icon name="Trash" /></Button>
					</div>
				</div>
			</div>
		</div>
		<DialogConfirm v-model="toggleConfirmationModal" @confirm="destroy">
			<template #title>¿Estás seguro de eliminar al usuario?</template>
			<template #description>
				Esta acción no se puede deshacer. Esto eliminará permanentemente tu
        cuenta.
			</template>
		</DialogConfirm>
	</AppLayout>
</template>


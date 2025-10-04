<script setup lang="ts">
import DialogConfirm from '@/components/DialogConfirm/DialogConfirm.vue';
import Icon from '@/components/Icon.vue';
import Button from '@/components/ui/button/Button.vue';
import { Input } from '@/components/ui/input';
import { Pagination, PaginationContent, PaginationEllipsis, PaginationItem, PaginationNext, PaginationPrevious } from '@/components/ui/pagination';
import AppLayout from '@/layouts/AppLayout.vue';
import { debounce } from '@/lib/shared/utils';
import type { Pagination as PaginationType, User } from '@/types';
import { Head, Link, router } from '@inertiajs/vue3';
import { ref, watch } from 'vue';
import { toast } from 'vue-sonner';

defineProps<{
	users: PaginationType<User>;
}>();

const toggleConfirmationModal = ref(false);
const userSelected = ref<User | null>(null);
const searchQuery = ref<string>('');

const breadcrumbs = [
	{ title: 'Dashboard', href: '/dashboard' },
	{ title: 'Usuarios', href: '/users' },
];

const destroy = () => {
	try {
		if (userSelected.value) {
			router.delete(route('admin.users.destroy', userSelected.value.id), { only: ['users'] });
			toast.success('Usuario eliminado exitosamente');
			userSelected.value = null;
			toggleConfirmationModal.value = false;
		}
	} catch (error) {
		toast.error('Ocurrió un error al eliminar el usuario');
		console.error(error);
	}
};

const selectUserForDeletion = (user: User) => {
	userSelected.value = user;
	toggleConfirmationModal.value = true;
};

const onChangePage = (pageUrl: string | null) => {
	if (pageUrl) {
		router.get(pageUrl, {}, { preserveState: true, only: ['users'] });
	}
};

const onClickNumberPage = (page: number) => {
	const pageUrl = route('admin.users.index', { page });
	onChangePage(pageUrl);
};

watch(
	searchQuery,
	debounce((newQuery) => {
		if (newQuery && newQuery.trim() !== '') {
			router.get(route('admin.users.index'), { search: newQuery }, { preserveState: true, only: ['users'] });
		} else {
			router.get(route('admin.users.index'), {}, { preserveState: true, only: ['users'] });
		}
	}, 500),
);
</script>

<template>

	<Head title="Usuarios" />
	<AppLayout :breadcrumbs="breadcrumbs">
		<div class="space-y-6 p-4">
			<div class="flex items-center justify-between">
				<h1 class="text-xl font-semibold">Usuarios</h1>
				<Link :href="route('admin.users.create')">
				<Button size="sm">Nuevo usuario</Button>
				</Link>
			</div>
			<div class="relative w-full max-w-sm items-center">
				<Input v-model="searchQuery" id="search" type="text" placeholder="Buscar..." class="pl-10" />
				<span class="absolute inset-y-0 start-0 flex items-center justify-center px-2">
					<Icon name="Search" class="size-6 text-muted-foreground" />
				</span>
			</div>
			<div class="divide-y rounded-xl border">
				<div v-for="user in users.data" :key="user.id" class="flex items-center justify-between p-3">
					<div>
						<div class="font-medium">
							{{ user.name }} <span class="text-xs text-muted-foreground"> ( {{ user.email }} ) </span>
						</div>
						<div class="text-sm text-muted-foreground">Tel: {{ user.phone }}</div>
					</div>
					<div class="flex gap-2">
						<Link :href="route('admin.users.show', user.id)">
						<Button variant="secondary" size="sm">
							<Icon name="Eye" />
						</Button>
						</Link>
						<Link :href="route('admin.users.edit', user.id)">
						<Button variant="secondary" size="sm">
							<Icon name="Edit" />
						</Button>
						</Link>
						<Button variant="destructive" size="sm" @click="selectUserForDeletion(user)">
							<Icon name="Trash" />
						</Button>
					</div>
				</div>
			</div>
			<Pagination v-slot="{ page }" :items-per-page="users.per_page" :total="users.total"
				:default-page="users.current_page">
				<PaginationContent v-slot="{ items }">
					<PaginationPrevious @click="onChangePage(users.prev_page_url)" />

					<template v-for="(item, index) in items" :key="index">
						<PaginationItem v-if="item.type === 'page'" :value="item.value" :is-active="item.value === page"
							@click="onClickNumberPage(item.value)">
							{{ item.value }}
						</PaginationItem>
						<PaginationEllipsis v-else-if="item.type === 'ellipsis'" />
					</template>

					<PaginationNext @click="onChangePage(users.next_page_url)" />
				</PaginationContent>
			</Pagination>
		</div>
		<DialogConfirm v-model="toggleConfirmationModal" @confirm="destroy">
			<template #title>¿Estás seguro de eliminar al usuario?</template>
			<template #description> Esta acción no se puede deshacer. Esto eliminará permanentemente tu cuenta. </template>
		</DialogConfirm>
	</AppLayout>
</template>

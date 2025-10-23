<script setup lang="ts">
import DialogConfirm from '@/components/DialogConfirm/DialogConfirm.vue';
import Icon from '@/components/Icon.vue';
import Button from '@/components/ui/button/Button.vue';
// import { Input } from '@/components/ui/input';
// import { Pagination, PaginationContent, PaginationEllipsis, PaginationItem, PaginationNext, PaginationPrevious } from '@/components/ui/pagination';
import AppLayout from '@/layouts/AppLayout.vue';
import { debounce } from '@/lib/shared/utils';
import { Head, Link, router } from '@inertiajs/vue3';
import { ref, watch } from 'vue';
import { toast } from 'vue-sonner';
import type { Branch } from '@/types';

defineProps<{
    branches: Branch[];
}>();

const toggleConfirmationModal = ref(false);
const branchSelected = ref<Branch | null>(null);
const searchQuery = ref<string>('');

const breadcrumbs = [
    { title: 'Dashboard', href: '/dashboard' },
    { title: 'Sucursales', href: '/admin/branches' },
];

const destroy = () => {
    try {
        if (branchSelected.value) {
            router.delete(route('admin.branches.destroy', branchSelected.value.id), { only: ['branches'] });
            toast.success('Sucursal eliminada exitosamente');
            branchSelected.value = null;
            toggleConfirmationModal.value = false;
        }
    } catch (error) {
        toast.error('Ocurrió un error al eliminar la sucursal');
        console.error(error);
    }
};

const selectBranchForDeletion = (branch: Branch) => {
    branchSelected.value = branch;
    toggleConfirmationModal.value = true;
};

// const onChangePage = (pageUrl: string | null) => {
//     if (pageUrl) {
//         router.get(pageUrl, {}, { preserveState: true, only: ['branches'] });
//     }
// };

// const onClickNumberPage = (page: number) => {
//     const pageUrl = route('admin.branches.index', { page });
//     onChangePage(pageUrl);
// };

watch(
    searchQuery,
    debounce((newQuery) => {
        if (newQuery && newQuery.trim() !== '') {
            router.get(route('admin.branches.index'), { search: newQuery }, { preserveState: true, only: ['branches'] });
        } else {
            router.get(route('admin.branches.index'), {}, { preserveState: true, only: ['branches'] });
        }
    }, 500),
);
</script>

<template>
    <Head title="Sucursales" />
    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="space-y-6 p-4">
            <div class="flex items-center justify-between">
                <h1 class="text-xl font-semibold">Sucursales</h1>
                <Link :href="route('admin.branches.create')">
                <Button size="sm">Nueva sucursal</Button>
                </Link>
            </div>
            <!-- <div class="relative w-full max-w-sm items-center">
                <Input v-model="searchQuery" id="search" type="text" placeholder="Buscar..." class="pl-10" />
                <span class="absolute inset-y-0 start-0 flex items-center justify-center px-2">
                    <Icon name="Search" class="size-6 text-muted-foreground" />
                </span>
            </div> -->
            <div class="divide-y rounded-xl border">
                <div v-for="branch in branches" :key="branch.id" class="flex items-center justify-between p-3">
                    <div class="flex items-center gap-4">
                        <div class="min-w-20 w-20 h-20 bg-gray-200 rounded-lg overflow-hidden flex items-center justify-center">
                        <img :src="branch.image" :alt="branch.name" class="object-cover w-full h-full" v-if="branch.image" />
                    </div>
                    <div>
                        <div class="font-medium">
                            {{ branch.name }} <span class="text-xs text-muted-foreground"> ( {{ branch.address ?? 'Sin dirección' }} ) </span>
                        </div>
                        <div class="text-sm text-muted-foreground">Estado: {{ branch.is_active ? 'Activa' : 'Inactiva' }}</div>
                    </div>
                    </div>
                    <div class="flex gap-2">
                        <Link :href="route('admin.branches.edit', branch.id)">
                        <Button variant="secondary" size="sm">
                            <Icon name="Edit" />
                        </Button>
                        </Link>
                        <Button variant="destructive" size="sm" @click="selectBranchForDeletion(branch)">
                            <Icon name="Trash" />
                        </Button>
                    </div>
                </div>
            </div>
            <!-- <Pagination v-slot="{ page }" :items-per-page="branches.per_page" :total="branches.total"
                :default-page="branches.current_page">
                <PaginationContent v-slot="{ items }">
                    <PaginationPrevious @click="onChangePage(branches.prev_page_url)" />

                    <template v-for="(item, index) in items" :key="index">
                        <PaginationItem v-if="item.type === 'page'" :value="item.value" :is-active="item.value === page"
                            @click="onClickNumberPage(item.value)">
                            {{ item.value }}
                        </PaginationItem>
                        <PaginationEllipsis v-else-if="item.type === 'ellipsis'" />
                    </template>

                    <PaginationNext @click="onChangePage(branches.next_page_url)" />
                </PaginationContent>
            </Pagination> -->
        </div>
        <DialogConfirm v-model="toggleConfirmationModal" @confirm="destroy">
            <template #title>¿Estás seguro de eliminar la sucursal?</template>
            <template #description> Esta acción no se puede deshacer. Esto eliminará permanentemente la sucursal. </template>
        </DialogConfirm>
    </AppLayout>
</template>

<script setup lang="ts">
import DialogConfirm from '@/components/DialogConfirm/DialogConfirm.vue';
import Icon from '@/components/Icon.vue';
import Button from '@/components/ui/button/Button.vue';
import { Input } from '@/components/ui/input';
import { Pagination, PaginationContent, PaginationEllipsis, PaginationItem, PaginationNext, PaginationPrevious } from '@/components/ui/pagination';
import AppLayout from '@/layouts/AppLayout.vue';
import { debounce } from '@/lib/shared/utils';
import type { Pagination as PaginationType } from '@/types';
import { Head, Link, router } from '@inertiajs/vue3';
import { ref, watch } from 'vue';
import { toast } from 'vue-sonner';

interface Category {
    id: number;
    name: string;
    description: string | null;
    created_at: string;
    updated_at: string;
}

interface Filters {
    search?: string;
}

const props = defineProps<{
    categories: PaginationType<Category>;
    filters: Filters;
}>();

const toggleConfirmationModal = ref(false);
const categorySelected = ref<Category | null>(null);
const searchQuery = ref<string>(props.filters.search || '');

const breadcrumbs = [
    { title: 'Dashboard', href: '/dashboard' },
    { title: 'Categorías', href: '/admin/categories' },
];

const destroy = () => {
    try {
        if (categorySelected.value) {
            router.delete(route('admin.categories.destroy', categorySelected.value.id), { only: ['categories'] });
            toast.success('Categoría eliminada exitosamente');
            categorySelected.value = null;
            toggleConfirmationModal.value = false;
        }
    } catch (error) {
        toast.error('Ocurrió un error al eliminar la categoría');
        console.error(error);
    }
};

const selectCategoryForDeletion = (category: Category) => {
    categorySelected.value = category;
    toggleConfirmationModal.value = true;
};

const onChangePage = (pageUrl: string | null) => {
    if (pageUrl) {
        router.get(pageUrl, {}, { preserveState: true, only: ['categories'] });
    }
};

const onClickNumberPage = (page: number) => {
    const pageUrl = route('admin.categories.index', { page });
    onChangePage(pageUrl);
};

watch(
    searchQuery,
    debounce((newQuery) => {
        if (newQuery && newQuery.trim() !== '') {
            router.get(route('admin.categories.index'), { search: newQuery }, { preserveState: true, only: ['categories'] });
        } else {
            router.get(route('admin.categories.index'), {}, { preserveState: true, only: ['categories'] });
        }
    }, 500),
);
</script>

<template>
    <Head title="Categorías" />
    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="space-y-6 p-4">
            <div class="flex items-center justify-between">
                <h1 class="text-xl font-semibold">Categorías</h1>
                <Link :href="route('admin.categories.create')">
                <Button size="sm">Nueva categoría</Button>
                </Link>
            </div>
            <div class="relative w-full max-w-sm items-center">
                <Input v-model="searchQuery" id="search" type="text" placeholder="Buscar..." class="pl-10" />
                <span class="absolute inset-y-0 start-0 flex items-center justify-center px-2">
                    <Icon name="Search" class="size-6 text-muted-foreground" />
                </span>
            </div>
            <div class="divide-y rounded-xl border">
                <div v-for="category in categories.data" :key="category.id" class="flex items-center justify-between p-3">
                    <div>
                        <div class="font-medium">
                            {{ category.name }} <span class="text-xs text-muted-foreground"> ( {{ category.description ?? 'Sin descripción' }} ) </span>
                        </div>
                    </div>
                    <div class="flex gap-2">
                        <Link :href="route('admin.categories.edit', category.id)">
                        <Button variant="secondary" size="sm">
                            <Icon name="Edit" />
                        </Button>
                        </Link>
                        <Button variant="destructive" size="sm" @click="selectCategoryForDeletion(category)">
                            <Icon name="Trash" />
                        </Button>
                    </div>
                </div>
            </div>
            <Pagination v-slot="{ page }" :items-per-page="categories.per_page" :total="categories.total"
                :default-page="categories.current_page">
                <PaginationContent v-slot="{ items }">
                    <PaginationPrevious @click="onChangePage(categories.prev_page_url)" />

                    <template v-for="(item, index) in items" :key="index">
                        <PaginationItem v-if="item.type === 'page'" :value="item.value" :is-active="item.value === page"
                            @click="onClickNumberPage(item.value)">
                            {{ item.value }}
                        </PaginationItem>
                        <PaginationEllipsis v-else-if="item.type === 'ellipsis'" />
                    </template>

                    <PaginationNext @click="onChangePage(categories.next_page_url)" />
                </PaginationContent>
            </Pagination>
        </div>
        <DialogConfirm v-model="toggleConfirmationModal" @confirm="destroy">
            <template #title>¿Estás seguro de eliminar la categoría?</template>
            <template #description> Esta acción no se puede deshacer. Esto eliminará permanentemente la categoría. </template>
        </DialogConfirm>
    </AppLayout>
</template>

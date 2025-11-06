<script setup lang="ts">
import DialogConfirm from '@/components/DialogConfirm/DialogConfirm.vue';
import Icon from '@/components/Icon.vue';
import Button from '@/components/ui/button/Button.vue';
import { Input } from '@/components/ui/input';
import { Pagination, PaginationContent, PaginationEllipsis, PaginationItem, PaginationNext, PaginationPrevious } from '@/components/ui/pagination';
import AppLayout from '@/layouts/AppLayout.vue';
import { debounce } from '@/lib/shared/utils';
import type { Auth, Pagination as PaginationType } from '@/types';
import { Head, Link, router } from '@inertiajs/vue3';
import { ref, watch } from 'vue';
import { toast } from 'vue-sonner';
import { useRole } from '@/composables/useRole';
import ShowVideo from './ShowVideo.vue';
import { Select, SelectContent, SelectItem, SelectTrigger, SelectValue } from '@/components/ui/select';
import type { ExerciseWithCategory, Category  } from '@/types/Exercise';

interface Filters {
    search?: string;
    category_id?: number;
}

const props = defineProps<{
    auth: Auth;
    exercises: PaginationType<ExerciseWithCategory>;
    filters: Filters;
    categories: Category[];
}>();

const { isAdmin } = useRole(props.auth.user.roles);

const toggleConfirmationModal = ref(false);
const toggleVideoModal = ref(false);
const exerciseSelected = ref<ExerciseWithCategory | null>(null);
const searchQuery = ref<string>(props.filters.search || '');
const selectedCategory = ref<number | null>(props.filters.category_id || null);

const breadcrumbs = [
    { title: 'Dashboard', href: '/dashboard' },
    { title: 'Ejercicios', href: '/admin/exercises' },
];

const destroy = () => {
    try {
        if (exerciseSelected.value) {
            router.delete(route('admin.exercises.destroy', exerciseSelected.value.id), {
                only: ['exercises'],
                data: {
                    search: searchQuery.value,
                    category_id: selectedCategory.value,
                }
            });
            toast.success('Ejercicio eliminado exitosamente');
            exerciseSelected.value = null;
            toggleConfirmationModal.value = false;
        }
    } catch (error) {
        toast.error('Ocurrió un error al eliminar el ejercicio');
        console.error(error);
    }
};

const selectExerciseForDeletion = (exercise: ExerciseWithCategory) => {
    exerciseSelected.value = exercise;
    toggleConfirmationModal.value = true;
};

const onChangePage = (pageUrl: string | null) => {
    if (pageUrl) {
        router.get(pageUrl, { search: searchQuery.value, category_id: selectedCategory.value }, { preserveState: true, only: ['exercises'] });
    }
};

const onClickNumberPage = (page: number) => {
    const pageUrl = route('exercises.index', { page, search: searchQuery.value, category_id: selectedCategory.value });
    onChangePage(pageUrl);
};

const selectExerciseForView = (exercise: ExerciseWithCategory) => {
    exerciseSelected.value = exercise;
    toggleVideoModal.value = true;
};

watch(
    searchQuery,
    debounce((newQuery) => {
        router.get(route('exercises.index'), { search: newQuery, category_id: selectedCategory.value }, { preserveState: true, only: ['exercises'] });
    }, 500),
);

watch(
    selectedCategory,
    debounce((newCategory) => {
        router.get(route('exercises.index'), { search: searchQuery.value, category_id: newCategory }, { preserveState: true, only: ['exercises'] });
    }, 500),
);
</script>

<template>

    <Head title="Ejercicios" />
    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="space-y-6 p-4">
            <div class="flex items-center justify-between">
                <h1 class="text-xl font-semibold">Ejercicios</h1>
                <div v-if="isAdmin" class="flex items-center gap-4">
                    <Link  :href="route('categories.index')">
                    <Button size="sm">Categorías</Button>
                    </Link>
                    <Link :href="route('admin.exercises.create')">
                    <Button size="sm">Nuevo ejercicio</Button>
                    </Link>
                </div>
            </div>
            <div class="flex gap-4 justify-between ">
                <div class="relative w-full max-w-sm items-center">
                    <Input v-model="searchQuery" id="search" type="text" placeholder="Buscar..." class="pl-10" />
                    <span class="absolute inset-y-0 start-0 flex items-center justify-center px-2">
                        <Icon name="Search" class="size-6 text-muted-foreground" />
                    </span>
                </div>
                <div class="w-full max-w-sm flex justify-end">
                    <Select v-model="selectedCategory">
                        <SelectTrigger class="w-[180px]">
                            <SelectValue placeholder="Filtrar por categoría" />
                        </SelectTrigger>
                        <SelectContent>
                            <SelectItem :value="null">Todas</SelectItem>
                            <SelectItem v-for="category in categories" :key="category.id" :value="category.id">
                                {{ category.name }}
                            </SelectItem>
                        </SelectContent>
                    </Select>
                </div>
            </div>
            <div class="divide-y rounded-xl border">
                <div v-for="exercise in exercises.data" :key="exercise.id"
                    class="flex items-center justify-between p-3">
                    <div>
                        <div class="font-medium">
                            {{ exercise.name }} <span class="text-xs text-muted-foreground"> ( {{ exercise.category.name
                                }} ) </span>
                        </div>
                        <!-- <div class="text-sm text-muted-foreground">{{ exercise.description ?? 'Sin descripción' }}</div> -->
                    </div>
                    <div class="flex gap-2">
                        <Link v-if="isAdmin" :href="route('admin.exercises.edit', exercise.id)">
                        <Button variant="secondary" size="sm">
                            <Icon name="Edit" />
                        </Button>
                        </Link>
                        <Button v-if="isAdmin" variant="destructive" size="sm"
                            @click="selectExerciseForDeletion(exercise)">
                            <Icon name="Trash" />
                        </Button>
                        <Button variant="default" size="sm" @click="selectExerciseForView(exercise)">
                            <Icon name="Eye" />
                        </Button>
                    </div>
                </div>
            </div>
            <Pagination v-slot="{ page }" :items-per-page="exercises.per_page" :total="exercises.total"
                :default-page="exercises.current_page">
                <PaginationContent v-slot="{ items }">
                    <PaginationPrevious @click="onChangePage(exercises.prev_page_url)" />

                    <template v-for="(item, index) in items" :key="index">
                        <PaginationItem v-if="item.type === 'page'" :value="item.value" :is-active="item.value === page"
                            @click="onClickNumberPage(item.value)">
                            {{ item.value }}
                        </PaginationItem>
                        <PaginationEllipsis v-else-if="item.type === 'ellipsis'" />
                    </template>

                    <PaginationNext @click="onChangePage(exercises.next_page_url)" />
                </PaginationContent>
            </Pagination>
        </div>
        <DialogConfirm v-model="toggleConfirmationModal" @confirm="destroy">
            <template #title>¿Estás seguro de eliminar el ejercicio?</template>
            <template #description> Esta acción no se puede deshacer. Esto eliminará permanentemente el ejercicio.
            </template>
        </DialogConfirm>
        <ShowVideo v-if="exerciseSelected" v-model="toggleVideoModal"
            :video-url="exerciseSelected.demonstration_video!"
            :title="exerciseSelected.name"
            :description="exerciseSelected.description" 
            />
    </AppLayout>
</template>

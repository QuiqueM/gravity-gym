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

interface Promotion {
  id: number;
  name: string;
  description: string | null;
  is_active: boolean;
  image: string | null;
  created_at: string;
  updated_at: string;
}

interface Filters {
  search?: string;
}

const props = defineProps<{
  promotions: PaginationType<Promotion>;
  filters: Filters;
}>();

const toggleConfirmationModal = ref(false);
const promotionSelected = ref<Promotion | null>(null);
const searchQuery = ref<string>(props.filters.search || '');

const breadcrumbs = [
  { title: 'Dashboard', href: '/dashboard' },
  { title: 'Promociones', href: '/promotions' },
];

const destroy = () => {
  try {
    if (promotionSelected.value) {
      router.delete(route('admin.promotions.destroy', promotionSelected.value.id), { only: ['promotions'] });
      toast.success('Promoción eliminada exitosamente');
      promotionSelected.value = null;
      toggleConfirmationModal.value = false;
    }
  } catch (error) {
    toast.error('Ocurrió un error al eliminar la promoción');
    console.error(error);
  }
};

const selectPromotionForDeletion = (promotion: Promotion) => {
  promotionSelected.value = promotion;
  toggleConfirmationModal.value = true;
};

const onChangePage = (pageUrl: string | null) => {
  if (pageUrl) {
    router.get(pageUrl, {}, { preserveState: true, only: ['promotions'] });
  }
};

const onClickNumberPage = (page: number) => {
  const pageUrl = route('admin.promotions.index', { page });
  onChangePage(pageUrl);
};

watch(
  searchQuery,
  debounce((newQuery) => {
    if (newQuery && newQuery.trim() !== '') {
      router.get(route('admin.promotions.index'), { search: newQuery }, { preserveState: true, only: ['promotions'] });
    } else {
      router.get(route('admin.promotions.index'), {}, { preserveState: true, only: ['promotions'] });
    }
  }, 500),
);
</script>

<template>

  <Head title="Promociones" />
  <AppLayout :breadcrumbs="breadcrumbs">
    <div class="space-y-6 p-4">
      <div class="flex items-center justify-between">
        <h1 class="text-xl font-semibold">Promociones</h1>
        <Link :href="route('admin.promotions.create')">
        <Button size="sm">Nueva promoción</Button>
        </Link>
      </div>
      <div class="relative w-full max-w-sm items-center">
        <Input v-model="searchQuery" id="search" type="text" placeholder="Buscar..." class="pl-10" />
        <span class="absolute inset-y-0 start-0 flex items-center justify-center px-2">
          <Icon name="Search" class="size-6 text-muted-foreground" />
        </span>
      </div>
      <div class="divide-y rounded-xl border">
        <div v-for="promotion in promotions.data" :key="promotion.id" class="flex items-center justify-between p-3">
          <div class="flex items-center gap-4">
            <div class="min-w-20 w-20 h-20 bg-gray-200 rounded-lg overflow-hidden flex items-center justify-center">
              <img :src="promotion.image" :alt="promotion.name" class="object-cover w-full h-full" v-if="promotion.image" />
            </div>
            <div class="flex flex-col">
              <div class="font-medium">
                {{ promotion.name }} 
              </div>
              <div class="text-sm text-muted-foreground">Estado: {{ promotion.is_active ? 'Activa' : 'Inactiva' }}</div>
            </div>
          </div>
          <div class="flex gap-2">
            <Link :href="route('admin.promotions.edit', promotion.id)">
            <Button variant="secondary" size="sm">
              <Icon name="Edit" />
            </Button>
            </Link>
            <Button variant="destructive" size="sm" @click="selectPromotionForDeletion(promotion)">
              <Icon name="Trash" />
            </Button>
          </div>
        </div>
      </div>
      <Pagination v-slot="{ page }" :items-per-page="promotions.per_page" :total="promotions.total"
        :default-page="promotions.current_page">
        <PaginationContent v-slot="{ items }">
          <PaginationPrevious @click="onChangePage(promotions.prev_page_url)" />

          <template v-for="(item, index) in items" :key="index">
            <PaginationItem v-if="item.type === 'page'" :value="item.value" :is-active="item.value === page"
              @click="onClickNumberPage(item.value)">
              {{ item.value }}
            </PaginationItem>
            <PaginationEllipsis v-else-if="item.type === 'ellipsis'" />
          </template>

          <PaginationNext @click="onChangePage(promotions.next_page_url)" />
        </PaginationContent>
      </Pagination>
    </div>
    <DialogConfirm v-model="toggleConfirmationModal" @confirm="destroy">
      <template #title>¿Estás seguro de eliminar la promoción?</template>
      <template #description> Esta acción no se puede deshacer. Esto eliminará permanentemente la promoción. </template>
    </DialogConfirm>
  </AppLayout>
</template>

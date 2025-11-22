<script setup lang="ts">
import { ref } from 'vue';
import AppLayout from '@/layouts/AppLayout.vue';
import { Head, Link, router } from '@inertiajs/vue3';
import type { BreadcrumbItem } from '@/types';
import Button from '@/components/ui/button/Button.vue';
import type { MembershipItem, Plan } from '@/types/membership';
import type { Pagination as PaginationType } from '@/types';
import Icon from '@/components/Icon.vue';
import { useDates } from '@/composables/useDates';
import {
  Select,
  SelectContent,
  SelectItem,
  SelectTrigger,
  SelectValue,
} from "@/components/ui/select"
import {
  Pagination,
  PaginationContent,
  PaginationEllipsis,
  PaginationItem,
  PaginationNext,
  PaginationPrevious,
} from "@/components/ui/pagination"

defineProps<{
  plans: Plan[];
  memberships: PaginationType<MembershipItem>;
}>();

const { formatDate, daysRemaining } = useDates();

const breadcrumbs: BreadcrumbItem[] = [
  { title: 'Dashboard', href: '/dashboard' },
  { title: 'Membresías', href: '/memberships' },
];

const filterSelected = ref<string | null>(null);
const typesFilters = ref([
  { id: 'all', name: 'Todos' },
  { id: 'active', name: 'Activos' },
  { id: 'expiring', name: 'Por vencer' },
  { id: 'expired', name: 'Vencidos' },
]);

const onFilterChange = (filter: string) => {
  console.log('Filtro seleccionado:', filter);
  router.get(route('memberships.index'), { filter }, { preserveState: true, only: ['memberships'] });
}

const labelStatus = (isActive: boolean) => {
  return isActive ? 'Activo' : 'Vencido';
}

const colorStatus = (date: string | Date): string => {
  const daysLeft = daysRemaining(date);
  if (daysLeft === 0) {
    return 'text-red-700'; // Vencido
  } else if (daysLeft <= 5) {
    return 'text-yellow-600'; // Por vencer
  } else {
    return 'text-green-600'; // Activo
  }
}

const onChangePage = (pageUrl: string | null) => {
  if (pageUrl) {
    const params: Record<string, any> = {};
    if (filterSelected.value) {
      params.filter = filterSelected.value;
    }
    router.get(pageUrl, params, { preserveState: true, only: ['memberships'] });
  }
}

const onClickNumberPage = (page: number) => {
  const pageUrl = route('memberships.index', { page });
  onChangePage(pageUrl);
}
</script>

<template>

  <Head title="Membresías" />
  <AppLayout :breadcrumbs="breadcrumbs">
    <div class="p-4 space-y-6">
      <div class="flex items-center justify-between">
        <h1 class="text-xl font-semibold">Planes</h1>
        <Link :href="route('memberships-plan.create')">
        <Button size="sm">Añadir</Button>
        </Link>
      </div>
      <div class="grid gap-4 md:grid-cols-3">
        <div v-for="plan in plans" :key="plan.id" class="rounded-xl border p-4">
          <div class="font-medium flex justify-between items-center">
            <span>{{ plan.name }}</span>
            <Link :href="route('memberships-plan.edit', plan.id)">
            <Icon name="Edit" />
            </Link>
          </div>
          <div class="text-sm text-muted-foreground">{{ plan.description }}</div>
          <div class="mt-2 text-sm">Duración: {{ plan.duration_days }} días</div>
          <div class="mt-1 text-sm">Precio: ${{ plan.price }}</div>
        </div>
      </div>

      <div class="flex flex-col gap-4">
        <div class="flex items-center justify-between">
          <h2 class="text-xl font-semibold mb-2">Membresías</h2>
          <div class="flex gap-2">
            <Select id="filter" v-model="filterSelected">
              <SelectTrigger class="w-full">
                <SelectValue placeholder="Filtrar por" />
              </SelectTrigger>
              <SelectContent>
                <SelectItem v-for="type in typesFilters" :key="type.id" :value="type.id"
                  @select="onFilterChange(type.id)">{{ type.name }}</SelectItem>
              </SelectContent>
            </Select>
          </div>
        </div>
        <div class="rounded-xl border divide-y">
          <div v-for="item in memberships.data" :key="item.id" class="flex items-center justify-between p-3">
            <div>
              <div class="font-medium">{{ item.user.name }} • {{ item.plan.name }}</div>
              <div class="text-sm text-muted-foreground">
                {{ formatDate(item.starts_at) }} → {{ formatDate(item.ends_at) }} • <span
                  :class="colorStatus(item.ends_at)"> {{ labelStatus(item.is_active) }} </span>
              </div>
            </div>
            <Button variant="secondary" size="sm">Ver</Button>
          </div>
        </div>
        <Pagination v-slot="{ page }" :items-per-page="memberships.per_page" :total="memberships.total" :default-page="memberships.current_page">
          <PaginationContent v-slot="{ items }">
            <PaginationPrevious @click="onChangePage(memberships.prev_page_url)" />

            <template v-for="(item, index) in items" :key="index">
              <PaginationItem v-if="item.type === 'page'" :value="item.value" :is-active="item.value === page" @click="onClickNumberPage(item.value)">
                {{ item.value }}
              </PaginationItem>
              <PaginationEllipsis v-else-if="item.type === 'ellipsis'" />
            </template>

            <PaginationNext @click="onChangePage(memberships.next_page_url)" />
          </PaginationContent>
        </Pagination>
      </div>
    </div>
  </AppLayout>
</template>

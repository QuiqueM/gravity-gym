<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { Head, Link, router } from '@inertiajs/vue3';
import { ref, watch, computed } from 'vue';
import type { BreadcrumbItem, User } from '@/types';
import type { Payment, PaymentStats } from '@/types/payment';
import { debounce } from '@/lib/shared/utils';
import { ShieldCheck, Info, Banknote, DollarSign } from 'lucide-vue-next';
import { useDates } from '@/composables/useDates';

import {
  Table,
  TableBody,
  TableCell,
  TableHead,
  TableHeader,
  TableRow,
} from "@/components/ui/table";
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import {
  Select,
  SelectContent,
  SelectItem,
  SelectTrigger,
  SelectValue,
} from '@/components/ui/select';
import { Pagination, PaginationContent, PaginationItem, PaginationNext, PaginationPrevious } from '@/components/ui/pagination';


interface Props {
  payments: {
    data: Payment[];
    links: { url: string | null; label: string; active: boolean }[];
    current_page: number;
    last_page: number;
    from: number;
    to: number;
    total: number;
  };
  filters: {
    status?: string;
    payment_method?: string;
    search?: string;
    date_from?: string;
    date_to?: string;
  };
  stats: PaymentStats;
  auth: {
    user: User;
  };
}

const props = defineProps<Props>();
const { transformTimeZone } = useDates();

const breadcrumbs: BreadcrumbItem[] = [
  { title: 'Dashboard', href: '/dashboard' },
  { title: 'Pagos', href: '#' },
];

const currentFilters = ref({
  status: props.filters.status || '',
  payment_method: props.filters.payment_method || '',
  search: props.filters.search || '',
  date_from: props.filters.date_from || '',
  date_to: props.filters.date_to || '',
});

const applyFilters = debounce(() => {
  router.get(route('admin.payments.index'), currentFilters.value, {
    preserveState: true,
    replace: true,
  });
}, 300);

watch(currentFilters.value, applyFilters);

const clearFilters = () => {
  currentFilters.value = {
    status: '',
    payment_method: '',
    search: '',
    date_from: '',
    date_to: '',
  };
  router.get(route('admin.payments.index'), {}, {
    preserveState: true,
    replace: true,
  });
};

const goToPage = (url: string | null) => {
  if (url) {
    router.get(url, currentFilters.value, {
      preserveState: true,
      replace: true,
    });
  }
};

const paymentStatusOptions = [
  { value: 'all', label: 'Todos' },
  { value: 'approved', label: 'Aprobado' },
  { value: 'pending', label: 'Pendiente' },
  { value: 'rejected', label: 'Rechazado' },
  { value: 'refunded', label: 'Reembolsado' },
  { value: 'cancelled', label: 'Cancelado' },
];

const paymentMethodOptions = [
  { value: 'all', label: 'Todos' },
  { value: 'card', label: 'Tarjeta' },
  { value: 'bank_transfer', label: 'Transferencia Bancaria' },
  { value: 'effective', label: 'Efectivo' },
  // Agrega más métodos de pago según los que uses
];

const totalRevenueFormatted = computed(() => {
  return props.stats.total_revenue.toLocaleString('es-MX', { style: 'currency', currency: 'MXN' });
});

const monthlyRevenueFormatted = computed(() => {
  return props.stats.monthly_revenue.toLocaleString('es-MX', { style: 'currency', currency: 'MXN' });
});

</script>

<template>

  <Head title="Pagos" />
  <AppLayout :breadcrumbs="breadcrumbs">
    <div class="flex flex-1 justify-center p-4 md:px-40">
      <div class="layout-content-container flex max-w-[960px] flex-1 flex-col gap-3">
        <div class="flex flex-wrap justify-between gap-3">
          <p class="tracking-light min-w-72 text-[32px] leading-tight font-bold text-white">Pagos</p>
          <div class="flex flex-wrap items-center gap-3">
            <Button variant="default" @click="router.get(route('payments.export', currentFilters))">
              Exportar a CSV
            </Button>
          </div>
        </div>

        <!-- Seccion de Estadísticas -->
        <div class="grid gap-4 md:grid-cols-2 lg:grid-cols-4 mt-6">
          <div class="p-6 bg-gray-800 rounded-xl border border-gray-700/50 shadow-lg 
             transition-all duration-300 hover:shadow-indigo-500/40 hover:scale-[1.01]">
            <div class="flex items-start justify-between border-b border-gray-700/50 pb-3 mb-4">
              <div>
                <h3 class="text-sm uppercase text-gray-400 tracking-wider">
                  Pagos Totales
                </h3>
              </div>
              <div class="bg-indigo-600/30 p-3 rounded-full flex items-center justify-center">
                <Info class="w-6 h-6 text-indigo-400" />
              </div>
            </div>
            <div class="mt-4">
              <p class="text-4xl font-extrabold text-indigo-400">
                {{ props.stats.total_payments }}
              </p>
            </div>
          </div>
          <div class="p-6 bg-gray-800 rounded-xl border border-gray-700/50 shadow-lg 
             transition-all duration-300 hover:shadow-blue-500/40 hover:scale-[1.01]">
            <div class="flex items-start justify-between border-b border-gray-700/50 pb-3 mb-4">
              <div>
                <h3 class="text-sm uppercase text-gray-400 tracking-wider">
                  Pagos Aprobados
                </h3>
              </div>
              <div class="bg-blue-600/30 p-3 rounded-full flex items-center justify-center">
                <ShieldCheck class="w-6 h-6 text-blue-400" />
              </div>
            </div>
            <div class="mt-4">
              <p class="text-4xl font-extrabold text-blue-400">
                {{ props.stats.approved_payments }}
              </p>
            </div>
          </div>
          <div class="p-6 bg-gray-800 rounded-xl border border-gray-700/50 shadow-lg 
             transition-all duration-300 hover:shadow-green-500/40 hover:scale-[1.01]">
            <div class="flex items-start justify-between border-b border-gray-700/50 pb-3 mb-4">
              <div>
                <h3 class="text-sm uppercase text-gray-400 tracking-wider">
                  Ingresos Totales
                </h3>
              </div>
              <div class="bg-green-600/30 p-3 rounded-full flex items-center justify-center">
                <Banknote class="w-6 h-6 text-green-400" />
              </div>
            </div>
            <div class="mt-4 flex items-baseline gap-2">
              <p class="text-3xl font-extrabold text-green-400">
                ${{ totalRevenueFormatted }}
              </p>  
              <span class="text-sm text-gray-400">MXN</span>
            </div>
          </div>
          <div class="p-6 bg-gray-800 rounded-xl border border-gray-700/50 shadow-lg 
             transition-all duration-300 hover:shadow-green-500/40 hover:scale-[1.01]">
            <div class="flex items-start justify-between border-b border-gray-700/50 pb-3 mb-4">
              <div>
                <h3 class="text-sm uppercase text-gray-400 tracking-wider">
                  Ingresos Este Mes
                </h3>
              </div>
              <div class="bg-green-600/30 p-3 rounded-full flex items-center justify-center">
                <DollarSign class="w-6 h-6 text-green-400" />
              </div>
            </div>
            <div class="mt-4 flex items-baseline gap-2">
              <p class="text-3xl font-extrabold text-green-400">
                ${{ monthlyRevenueFormatted }}
              </p>  
              <span class="text-sm text-gray-400">MXN</span>
            </div>
          </div>
        </div>

        <!-- Sección de Filtros -->
        <div class="mt-6 p-4 bg-gray-800 rounded-lg shadow-md flex flex-wrap items-center gap-4">
          <Input v-model="currentFilters.search" placeholder="Buscar por usuario..."
            class="max-w-xs bg-gray-700 text-white border-gray-600" />

          <Select v-model="currentFilters.status">
            <SelectTrigger class="w-[180px] bg-gray-700 text-white border-gray-600">
              <SelectValue placeholder="Filtrar por estado" />
            </SelectTrigger>
            <SelectContent class="bg-gray-700 text-white border-gray-600">
              <SelectItem v-for="option in paymentStatusOptions" :key="option.value" :value="option.value">
                {{ option.label }}
              </SelectItem>
            </SelectContent>
          </Select>

          <Select id="payment_method" v-model="currentFilters.payment_method">
            <SelectTrigger class="w-[200px] bg-gray-700 text-white border-gray-600">
              <SelectValue placeholder="Filtrar por método" />
            </SelectTrigger>
            <SelectContent class="bg-gray-700 text-white border-gray-600">
              <SelectItem v-for="option in paymentMethodOptions" :key="option.value" :value="option.value">
                {{ option.label }}
              </SelectItem>
            </SelectContent>
          </Select>

          <Input type="date" v-model="currentFilters.date_from" class="w-[180px] bg-gray-700 text-white border-gray-600"
            title="Fecha desde" />
          <Input type="date" v-model="currentFilters.date_to" class="w-[180px] bg-gray-700 text-white border-gray-600"
            title="Fecha hasta" />

          <Button @click="clearFilters" variant="outline"
            class="bg-gray-600 text-white border-gray-500 hover:bg-gray-500">
            Limpiar filtros
          </Button>
        </div>

        <!-- Tabla de Pagos -->
        <div class="overflow-x-auto mt-6 rounded-lg shadow-md">
          <Table class="min-w-full divide-y divide-gray-700 bg-gray-800 text-white">
            <TableHeader class="bg-gray-700">
              <TableRow>
                <TableHead class="px-6 py-3 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">ID
                </TableHead>
                <TableHead class="px-6 py-3 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">
                  Usuario
                </TableHead>
                <TableHead class="px-6 py-3 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">Plan
                </TableHead>
                <TableHead class="px-6 py-3 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">Monto
                </TableHead>
                <TableHead class="px-6 py-3 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">Método
                </TableHead>
                <TableHead class="px-6 py-3 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">Estado
                </TableHead>
                <TableHead class="px-6 py-3 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">Fecha
                </TableHead>
                <TableHead class="px-6 py-3"></TableHead>
              </TableRow>
            </TableHeader>
            <TableBody class="divide-y divide-gray-700">
              <TableRow v-for="payment in props.payments.data" :key="payment.id">
                <TableCell class="px-6 py-4 whitespace-nowrap text-sm font-medium text-white">
                  {{ payment.id }}
                </TableCell>
                <TableCell class="px-6 py-4 whitespace-nowrap text-sm text-gray-300">
                  {{ payment.user?.name || 'N/A' }} ({{ payment.user?.email || 'N/A' }})
                </TableCell>
                <TableCell class="px-6 py-4 whitespace-nowrap text-sm text-gray-300">
                  {{ payment.membership_plan?.name || 'N/A' }}
                </TableCell>
                <TableCell class="px-6 py-4 whitespace-nowrap text-sm text-white">
                  $ {{ payment.amount.toLocaleString('es-MX', { style: 'currency', currency: payment.currency }) }}
                </TableCell>
                <TableCell class="px-6 py-4 whitespace-nowrap text-sm text-gray-300">
                  {{ payment.payment_method }}
                </TableCell>
                <TableCell class="px-6 py-4 whitespace-nowrap text-sm">
                  <span :class="{
                    'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300': payment.status === 'approved',
                    'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-300': payment.status === 'pending' || payment.status === 'in_process',
                    'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-300': payment.status === 'rejected' || payment.status === 'cancelled' || payment.status === 'refunded',
                  }" class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full capitalize">
                    {{ payment.status }}
                  </span>
                </TableCell>
                <TableCell class="px-6 py-4 whitespace-nowrap text-sm text-gray-300">
                  {{ transformTimeZone(payment.created_at)}}
                </TableCell>
                <TableCell class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                  <Link :href="route('admin.payments.show', payment.id)" class="text-blue-400 hover:text-blue-300">
                  Ver
                  </Link>
                </TableCell>
              </TableRow>
              <TableRow v-if="props.payments.data.length === 0">
                <TableCell colspan="8" class="text-center px-6 py-4 text-sm text-gray-400">
                  No se encontraron pagos.
                </TableCell>
              </TableRow>
            </TableBody>
          </Table>
        </div>

        <!-- Paginación -->
        <Pagination v-if="props.payments.links.length > 3" class="mt-4 flex justify-center">
          <PaginationContent>
            <PaginationItem>
              <PaginationPrevious :href="props.payments.links[0].url"
                @click.prevent="goToPage(props.payments.links[0].url)" />
            </PaginationItem>
            <PaginationItem v-for="(link, index) in props.payments.links.slice(1, -1)" :key="index">
              <Button :variant="link.active ? 'secondary' : 'ghost'"
                :class="{ 'text-white bg-blue-600 hover:bg-blue-700': link.active }"
                @click.prevent="goToPage(link.url)">
                {{ link.label }}
              </Button>
            </PaginationItem>
            <PaginationItem>
              <PaginationNext :href="props.payments.links[props.payments.links.length - 1].url"
                @click.prevent="goToPage(props.payments.links[props.payments.links.length - 1].url)" />
            </PaginationItem>
          </PaginationContent>
        </Pagination>
      </div>
    </div>
  </AppLayout>
</template>

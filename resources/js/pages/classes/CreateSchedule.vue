<script setup lang="ts">
import { useForm, Head, Link } from '@inertiajs/vue3';
import AppLayout from '@/layouts/AppLayout.vue';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import InputError from '@/components/InputError.vue';
import { Checkbox } from "@/components/ui/checkbox"
import type { ScheduleProps, ScheduleForm } from '@/types/Schedule';
import type { BreadcrumbItem } from '@/types';
import {
  Select,
  SelectContent,
  SelectItem,
  SelectTrigger,
  SelectValue,
} from "@/components/ui/select"
import { toast } from 'vue-sonner'

const props = defineProps<ScheduleProps>();

const breadcrumbs: BreadcrumbItem[] = [
  { title: 'Dashboard', href: '/dashboard' },
  { title: 'Clases', href: '/classes' },
  { title: 'Crear horario', href: '#' },
];

const form = useForm<ScheduleForm>({
	class_id: props.classSelected.id,
	starts_at: '',
	ends_at: '',
	room: 'Gravity',
	repeat: false,
	repeat_days: [],
	repeat_months: 1,
});

const days = [
  { label: 'Lunes', value: 'monday' },
  { label: 'Martes', value: 'tuesday' },
  { label: 'Miércoles', value: 'wednesday' },
  { label: 'Jueves', value: 'thursday' },
  { label: 'Viernes', value: 'friday' },
	{ label: 'Sábado', value: 'saturday' },
	{ label: 'Domingo', value: 'sunday' },
];

const submit = () => {
	form.post(route('classes.schedules.store', props.classSelected.id), {
		onSuccess: () => {
			form.reset();
			toast.success('Horario creado exitosamente');
		},
		onError: () => {
			toast.error('Ocurrió un error al crear el horario');
		}
	});
};

const toggleDays = (dayValue: string) => {
	const index = form.repeat_days.indexOf(dayValue);
	if (index > -1) {
		form.repeat_days.splice(index, 1);
	} else {
		form.repeat_days.push(dayValue);
	}
};
</script>

<template>
	<Head title="Crear horario" />
	<AppLayout :breadcrumbs="breadcrumbs">
		<form @submit.prevent="submit" class="w-full flex flex-col gap-4 p-4">
			<div class="flex gap-4">
				<div class="grid gap-4 w-1/2">
					<div class="grid gap-2">
						<Label for="class_id">Clase</Label>
						<Input id="class_id" :value="classSelected.title" required disabled :placeholder="classSelected.title" />
						<InputError :message="form.errors.class_id" />
					</div>
					<div class="grid gap-2">
						<Label for="starts_at">Inicio</Label>
						<Input id="starts_at" type="datetime-local" v-model="form.starts_at" required />
						<InputError :message="form.errors.starts_at" />
					</div>
					<div class="grid gap-2">
						<Label for="ends_at">Fin</Label>
						<Input id="ends_at" type="datetime-local" v-model="form.ends_at" required />
						<InputError :message="form.errors.ends_at" />
					</div>
					<div class="grid gap-2">
						<Label for="room">Lugar</Label>
						<Input id="room" v-model="form.room" placeholder="Ej: Sala 1" />
						<InputError :message="form.errors.room" />
					</div>
					<div class="flex items-center gap-2">
						<Checkbox id="repeat" v-model="form.repeat" />
						<Label for="repeat">Repetir horario</Label>
					</div>
				</div>
				<div v-if="form.repeat" class="flex flex-col gap-3 w-1/2">
					<Label>Repetir en:</Label>
					<div v-for="day in days" :key="day.value" class="flex items-center space-x-2">
						<Checkbox :id="'day-' + day.value" :name="'day-' + day.value" :model-value="form.repeat_days.includes(day.value)" @update:model-value="toggleDays(day.value)" />
						<Label :for="'day-' + day.value" class="mb-0">{{ day.label }}</Label>
					</div>
					<div class="grid gap-2">
							<Label for="repeat_months">Selecciona por cuantos meses se repite</Label>
							<Select id="repeat_months" v-model="form.repeat_months">
								<SelectTrigger class="w-full">
									<SelectValue placeholder="Selecciona un mes" />
								</SelectTrigger>
								<SelectContent>
									<SelectItem v-for="month in 12" :key="month" :value="month">{{ month }}</SelectItem>
								</SelectContent>
							</Select>
							<InputError :message="form.errors.repeat_months" />
						</div>
				</div>
			</div>
			<div class="flex w-full justify-between">
				<Link :href="route('classes.index')">
					<Button variant="outline" class="mt-2 w-full md:w-48">Cancelar</Button>
				</Link>
				<Button type="submit" class="mt-2 w-full md:w-48" :disabled="form.processing">Crear horario</Button>
			</div>
		</form>
	</AppLayout>
</template>

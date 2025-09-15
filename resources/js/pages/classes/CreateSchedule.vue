<script setup lang="ts">
import { ref } from 'vue';
import { useForm } from '@inertiajs/vue3';
import AppLayout from '@/layouts/AppLayout.vue';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import InputError from '@/components/InputError.vue';
import { Checkbox } from "@/components/ui/checkbox"
import type { ScheduleProps } from '@/types/Schedule';

const props = defineProps<ScheduleProps>();

const form = useForm({
	class_id: props.classSelected.id,
	starts_at: '',
	ends_at: '',
	room: '',
	repeat: false,
	repeat_days: [],
});

const days = [
  { label: 'Lunes', value: 1 },
  { label: 'Martes', value: 2 },
  { label: 'Miércoles', value: 3 },
  { label: 'Jueves', value: 4 },
  { label: 'Viernes', value: 5 },
];

const submit = () => {
	form.post(route('class-schedules.store'));
};

const toggleDays = (dayValue: number) => {
	const index = form.repeat_days.indexOf(dayValue);
	if (index > -1) {
		form.repeat_days.splice(index, 1);
	} else {
		form.repeat_days.push(dayValue);
	}
};
</script>

<template>
	<AppLayout>
		<form @submit.prevent="submit" class="w-full flex gap-4 p-4">
			<div class="grid gap-4 w-1/2">
				<div class="grid gap-2">
					<Label for="class_id">Clase</Label>
					<Input id="class_id" :value="classSelected.title" required disabled placeholder="ID de la clase" />
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
					<Label for="room">Sala</Label>
					<Input id="room" v-model="form.room" placeholder="Ej: Sala 1" />
					<InputError :message="form.errors.room" />
				</div>
				<div class="flex items-center gap-2">
					<input id="repeat" type="checkbox" v-model="form.repeat" />
					<Label for="repeat">Repetir de lunes a viernes</Label>
				</div>
				<Button type="submit" :disabled="form.processing">Crear horario</Button>
			</div>
			<div>
				<div v-for="day in days" :key="day.value" class="flex items-center space-x-2">
					<Checkbox :id="'day-' + day.value" :name="'day-' + day.value" :model-value="form.repeat_days.includes(day.value)" @update:model-value="toggleDays(day.value)" />
					<Label :for="'day-' + day.value" class="mb-0">{{ day.label }}</Label>
				</div>
			</div>
		</form>
	</AppLayout>
</template>

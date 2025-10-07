<script lang="ts" setup>
import { useForm } from '@inertiajs/vue3';
import {
  Sheet,
  SheetContent,
  SheetDescription,
  SheetFooter,
  SheetHeader,
  SheetTitle,
} from "@/components/ui/sheet"
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import InputError from '@/components/InputError.vue';
import { Button } from '@/components/ui/button';
import type { User } from '@/types';
import { toast } from 'vue-sonner';

const props = defineProps<{
  user: User;
}>();

const emit = defineEmits<{
  (e: 'update'): void;
}>();

const showSheet = defineModel<boolean>({ default: false });
const form = useForm({
  name: props.user.name,
  phone: props.user.phone,
});


const submit = () => {
  form.patch(route('profile.update', props.user.id), {
    preserveScroll: true,
    onSuccess: () => {
      toast.success('Perfil actualizado correctamente');
      showSheet.value = false;
      emit('update');
    },
    onError: () => {
      toast.error('Ocurrió un error al actualizar el perfil');
    },
  });
}

</script>

<template>
  <Sheet v-model:open="showSheet">
    <SheetContent side="right" class="w-[calc(100vw-5rem)] sm:w-96">
      <form @submit.prevent="submit">
        <SheetHeader>
          <SheetTitle>Editar perfil</SheetTitle>
          <SheetDescription>
            Aquí puedes editar la información del perfil del usuario.
          </SheetDescription>
        </SheetHeader>
        <div class="grid gap-4 p-4">
          <div class="grid gap-2">
            <Label for="name">Nombre</Label>
            <Input id="name" class="mt-1 block w-full" v-model="form.name" required autocomplete="name"
              placeholder="Nombre completo" />
            <InputError class="mt-2" :message="form.errors.name" />
          </div>
          <div class="grid gap-2">
            <Label for="phone">Telefono</Label>
            <Input id="phone" class="mt-1 block w-full" v-model="form.phone" required autocomplete="phone"
              placeholder="Telefono" />
            <InputError class="mt-2" :message="form.errors.phone" />
          </div>
        </div>
        <SheetFooter>
          <div class="flex items-center gap-4">
            <Button :disabled="form.processing">Guardar</Button>

            <Transition enter-active-class="transition ease-in-out" enter-from-class="opacity-0"
              leave-active-class="transition ease-in-out" leave-to-class="opacity-0">
              <p v-show="form.recentlySuccessful" class="text-sm text-neutral-600">Guardado.</p>
            </Transition>
          </div>
        </SheetFooter>
      </form>
    </SheetContent>
  </Sheet>
</template>
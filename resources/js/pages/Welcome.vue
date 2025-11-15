<script setup lang="ts">
import { ref, computed } from 'vue';
import { Head, Link } from '@inertiajs/vue3';
import NavBar from '@/components/navbar/NavBar.vue';
import MembershipCard from '@/components/MembershipCard/MembershipCard.vue';
import CommentUser from '@/components/CommentUser/CommentUser.vue';
import ShowMedia from '@/components/ShowMedia/ShowMedia.vue';
import ModalComments from '@/components/ModalComments/ModalComments.vue';
import type { User, Promotion, Branch } from '@/types';
import type { CommentWithUser } from '@/types/Comments';
import { Plan } from '@/types/membership';
import type { Event } from '@/types/Event';
import { Button } from '@/components/ui/button';

const props = defineProps<{
  auth: {
    user: User | null;
  };
  comments: CommentWithUser[];
  plans: Plan[];
  events: Event[];
  promotions: Promotion[];
  branches: Branch[];
}>();

const eventSelected = ref<Event | null>(null);
const promotionSelected = ref<Promotion | null>(null);
const showEvent = ref(false);
const showPromotions = ref(false);
const showModalComments = ref(false);

const onEventSelect = (event: Event) => {
  eventSelected.value = event;
  showEvent.value = true;
};

const onPromotionSelect = (promotion: Promotion) => {
  promotionSelected.value = promotion;
  showPromotions.value = true;
};


const visibleComments = computed(() => {
  return props.comments.slice(0, 5);
});

const showComments = () => {
  showModalComments.value = true;
};
</script>

<template>
    <Head title="Home">
      <link rel="preconnect" href="https://rsms.me/" />
      <link rel="stylesheet" href="https://rsms.me/inter/inter.css" />
    </Head>
    <div class="relative flex flex-col min-h-screen bg-black dark group/design-root overflow-x-hidden" style='font-family: Lexend, "Noto Sans", sans-serif;'>
      <div class="layout-container flex flex-1 flex-col">
        <NavBar :user="auth.user" />
        <div class="md:px-40 flex flex-1 justify-center py-5">
          <div class="layout-content-container flex flex-col w-full max-w-[960px] flex-1">
            <div class="@container">
              <div class="@[480px]:p-4">
                <div
                  class="flex min-h-[480px] flex-col gap-6 bg-cover bg-center bg-no-repeat @[480px]:gap-8 @[480px]:rounded-xl items-start justify-end px-4 pb-10 @[480px]:px-10"
                  style='background-image: linear-gradient(rgba(0, 0, 0, 0.1) 0%, rgba(0, 0, 0, 0.4) 100%), url("https://lh3.googleusercontent.com/aida-public/AB6AXuCNYmoESzgtDoT004tL2A9uwYgjge76FOWYmso1ihJq0t4ayX2JdHIGTX1mwVCwweeZRxMYvQQXl_A4vSXRUbqL4-OeXLw183Bl8G5lOiOGRS-6mL6YOHYm9MK4rKPvn18otZk23KGzGbKblpmrUkVsFvlvh82Fvbareenr8WSL6ukE5b5AyR2MdKZtkWTlaL0Jjq_g4TfQWiwMk7pXoRG1p49ZDL2v65qtHgRIjc_rOSPDHTZ5hUqJkhoEY4hWtW1y0bMGnhZoFcY");'
                >
                  <div class="flex flex-col gap-2 text-left">
                    <h1
                      class="text-white text-4xl font-black leading-tight tracking-[-0.033em] @[480px]:text-5xl @[480px]:font-black @[480px]:leading-tight @[480px]:tracking-[-0.033em]"
                    >
                      Eleva tu experiencia fitness con Gravity
                    </h1>
                    <h2 class="text-white text-sm font-normal leading-normal @[480px]:text-base @[480px]:font-normal @[480px]:leading-normal">
                      Experimenta el destino fitness definitivo con las mejores instalaciones, entrenadores expertos y una comunidad vibrante.
                    </h2>
                  </div>
                  <Link
                     :href="route('register')"
                    class="flex min-w-[84px] max-w-[480px] cursor-pointer items-center justify-center overflow-hidden rounded-full h-10 px-4 @[480px]:h-12 @[480px]:px-5 bg-primary text-white text-sm font-bold leading-normal tracking-[0.015em] @[480px]:text-base @[480px]:font-bold @[480px]:leading-normal @[480px]:tracking-[0.015em]"
                  >
                    <span class="truncate">Únete ahora</span>
                  </Link>
                </div>
              </div>
            </div>
            <h2 v-if="promotions.length" class="text-white text-[22px] font-bold leading-tight tracking-[-0.015em] px-4 pb-3 pt-5">Promociones</h2>
            <div class="flex overflow-y-auto [-ms-scrollbar-style:none] [scrollbar-width:none] [&amp;::-webkit-scrollbar]:hidden">
              <div class="flex items-stretch p-4 gap-3">
                <div v-for="promotion in promotions" :key="promotion.id" class="flex h-full flex-1 flex-col gap-4 rounded-lg min-w-60" @click="onPromotionSelect(promotion)">
                  <div
                    class="w-full bg-center bg-no-repeat aspect-video bg-cover rounded-xl flex flex-col"
                    :style="`background-image: url(${promotion.image});`"
                  ></div>
                  <div>
                    <p class="text-white text-base font-medium leading-normal">{{ promotion.name }}</p>
                    <p class="text-[#b9a89d] text-sm font-normal leading-normal">{{ promotion.description }}</p>
                  </div>
                </div>
              </div>
            </div>
            <h2 v-if="events.length" class="text-white text-[22px] font-bold leading-tight tracking-[-0.015em] px-4 pb-3 pt-5">Proximos Eventos</h2>
            <div class="flex overflow-y-auto [-ms-scrollbar-style:none] [scrollbar-width:none] [&amp;::-webkit-scrollbar]:hidden">
              <div class="flex items-stretch p-4 gap-3">
                <div v-for="event in events" :key="event.id" class="flex h-full flex-1 flex-col gap-4 rounded-lg max-w-60" @click="onEventSelect(event)">
                  <div class="w-full bg-center bg-cover rounded-xl flex flex-col">
                    <img :src="event.image" :alt="event.title" class="rounded-xl"/>
                  </div>
                  <div>
                    <p class="text-white text-base font-medium leading-normal">{{ event.title }}</p>
                    <p class="text-[#b9a89d] text-sm font-normal leading-normal line-clamp-2">{{ event.description }}</p>
                  </div>
                </div>
              </div>
            </div>
            <h2 v-if="branches.length" class="text-white text-[22px] font-bold leading-tight tracking-[-0.015em] px-4 pb-3 pt-5">Ubicación</h2>
            <div class="flex overflow-y-auto [-ms-scrollbar-style:none] [scrollbar-width:none] [&amp;::-webkit-scrollbar]:hidden">
              <div class="flex items-stretch p-4 gap-3">
                <div v-for="branch in branches" :key="branch.id" class="flex h-full flex-1 flex-col gap-4 rounded-lg min-w-60">
                  <div
                    class="w-full bg-center bg-no-repeat aspect-video bg-cover rounded-xl flex flex-col"
                    :style="`background-image: url(${branch.image});`"
                  ></div>
                  <div>
                    <p class="text-white text-base font-medium leading-normal">{{ branch.name }}</p>
                    <p class="text-[#b9a89d] text-sm font-normal leading-normal">{{ branch.address }}</p>
                  </div>
                </div>
              </div>
            </div>

            <h2 v-if="comments.length" class="text-white text-[22px] font-bold leading-tight tracking-[-0.015em] px-4 pb-3 pt-5">Reseñas de los clientes</h2>
            <div class="flex flex-col gap-8 overflow-x-hidden p-4">
              <CommentUser v-for="(comment, index) in visibleComments" :key="index" :comment="comment" />
              <div v-if="true" class="flex justify-center">
                <Button 
                  variant="outline"
                  class="text-white border-white hover:bg-white hover:text-black"
                  @click="showComments"
                >
                  Ver Todos
                </Button>
              </div>
            </div>
            <h2 class="text-white text-[22px] font-bold leading-tight tracking-[-0.015em] px-4 pb-3 pt-5">Opciones de Membresía</h2>
            <div class="grid grid-cols-[repeat(auto-fit,minmax(228px,1fr))] gap-2.5 px-4 py-3 @3xl:grid-cols-4">
              <MembershipCard
                v-for="(plan, index) in plans" :key="index"
                :title="plan.name"
                :price="plan.price"
                :frequency="plan.duration_days"
                :features="plan.features"
              />
            </div>
          </div>
        </div>
        <footer class="w-full mt-auto flex justify-center">
          <div class="flex max-w-[960px] flex-1 flex-col">
            <footer class="flex flex-col gap-6 px-5 pt-5 text-center @container">
              <!-- <div class="flex flex-wrap items-center justify-center gap-6 @[480px]:flex-row @[480px]:justify-around">
                <a class="text-[#b9a89d] text-base font-normal leading-normal min-w-40" href="#">Contact Us</a>
                <a class="text-[#b9a89d] text-base font-normal leading-normal min-w-40" href="#">Privacy Policy</a>
                <a class="text-[#b9a89d] text-base font-normal leading-normal min-w-40" href="#">Terms of Service</a>
              </div> -->
              <p class="text-[#b9a89d] text-base font-normal leading-normal">© {{ new Date().getFullYear() }} Gravity Fit MX. Todos los derechos reservados.</p>
              <span class="text-[#b9a89d] text-xs font-normal leading-normal">Hecho con ❤️ por <a href="https://github.com/QuiqueM" class="text-primary">Quique
              </a> para Gravity Fit MX</span>
             
            </footer>
          </div>
        </footer>

        <ShowMedia
          v-if="eventSelected"
          v-model:open="showEvent"
          :title="eventSelected.title"
          :imgUrl="eventSelected.image!"
          :description="eventSelected.description"
        >
          <template #footer>
            <Link v-if="!auth.user" @click="route('login')">
              <Button class="w-full">Inicia sesión</Button>
            </Link>
            <Link v-else :href="route('classes.schedules', eventSelected.class_id)">
              <Button class="w-full">Registrate</Button>
            </Link>
          </template>
        </ShowMedia>
        <ModalComments v-model="showModalComments" :comments="comments" />
      </div>
    </div>
</template>

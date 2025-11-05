<script setup lang="ts">
import NavMain from '@/components/NavMain.vue';
import NavUser from '@/components/NavUser.vue';
import { Sidebar, SidebarContent, SidebarFooter, SidebarHeader, SidebarMenu, SidebarMenuButton, SidebarMenuItem } from '@/components/ui/sidebar';
import { type NavItem } from '@/types';
import { Link } from '@inertiajs/vue3';
import { LayoutGrid, Users2, CalendarClock, CheckCircle2, CreditCard, IdCard, House, PartyPopper, BadgePercent, Dumbbell } from 'lucide-vue-next';
import AppLogo from './AppLogo.vue';
import { usePage } from '@inertiajs/vue3'
import { useRole } from '@/composables/useRole';
const user = usePage().props.auth.user

const { isAdmin, isCoach, isMember } = useRole(user.roles)

const mainNavItems: NavItem[] = [
    {
        title: 'Dashboard',
        href: '/dashboard',
        icon: LayoutGrid,
        isVisible: true,
    },
    {
        title: 'Membresías',
        href: '/memberships',
        icon: IdCard,
        isVisible: isAdmin.value,
    },
    {
        title: 'Tu Membresía',
        href: '/my-membership',
        icon: IdCard,
        isVisible: isMember.value,
    },
    {
        title: 'Clases',
        href: '/classes',
        icon: CalendarClock,
        isVisible: true,
    },
    {
        title: 'Tus Asistencias',
        href: '/my-attendances',
        icon: CalendarClock,
        isVisible: isMember.value,
    },
    {
        title: 'Asistencias',
        href: '/attendance',
        icon: CheckCircle2,
        isVisible: isAdmin.value || isCoach.value,
    },
    {
        title: 'Pagos',
        href: '/payments/openpay',
        icon: CreditCard,
        isVisible: isAdmin.value,
    },
    {
        title: 'Usuarios',
        href: '/users',
        icon: Users2,
        isVisible: isAdmin.value,
    },
    {
        title: 'Sucursales',
        href: '/branches',
        icon: House,
        isVisible: isAdmin.value,
    },
    {
        title: 'Eventos',
        href: '/events',
        icon: PartyPopper,
        isVisible: isAdmin.value,
    },
    {
        title: 'Promociones',
        href: '/promotions',
        icon: BadgePercent,
        isVisible: isAdmin.value,
    },
    {
        title: 'Ejercicios',
        href: '/exercises',
        icon: Dumbbell,
        isVisible: true,
    },
];

</script>

<template>
    <Sidebar collapsible="icon" variant="inset">
        <SidebarHeader>
            <SidebarMenu>
                <SidebarMenuItem>
                    <SidebarMenuButton size="lg" as-child>
                        <Link :href="route('home')">
                            <AppLogo />
                        </Link>
                    </SidebarMenuButton>
                </SidebarMenuItem>
            </SidebarMenu>
        </SidebarHeader>

        <SidebarContent>
            <NavMain :items="mainNavItems" />
        </SidebarContent>

        <SidebarFooter>
            <NavUser />
        </SidebarFooter>
    </Sidebar>
    <slot />
</template>

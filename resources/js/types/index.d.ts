import type { LucideIcon } from 'lucide-vue-next';
import type { Config } from 'ziggy-js';
import type { Plan } from './membership';

export interface Auth {
    user: User;
}

export interface BreadcrumbItem {
    title: string;
    href: string;
}

export interface NavItem {
    title: string;
    href: string;
    icon?: LucideIcon;
    isActive?: boolean;
    isVisible?: boolean;
}

export type AppPageProps<T extends Record<string, unknown> = Record<string, unknown>> = T & {
    name: string;
    quote: { message: string; author: string };
    auth: Auth;
    ziggy: Config & { location: string };
    sidebarOpen: boolean;
};

export interface User {
    id: number;
    name: string;
    email: string;
    avatar?: string;
    email_verified_at: string | null;
    created_at: string;
    updated_at: string;
    roles: string[];
    phone: string
}

export type BreadcrumbItemType = BreadcrumbItem;

export interface Pagination<T> {
    data: T[];
    current_page: number;
    first_page_url: string;
    from: number;
    last_page: number;
    last_page_url: string;
    links: { url: string | null; label: string; active: boolean }[];
    next_page_url: string | null;
    per_page: number;
    prev_page_url: string | null;
    to: number;
    total: number;
}

export interface UserWhithMembership extends User {
    membership: {
        id: number;
        name: string;
        description: string;
        price: number;
        duration_days: number;
        created_at: string;
        updated_at: string;
        plan: Plan;
    } | null;
}

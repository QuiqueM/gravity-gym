import { Role } from '@/types/enums/Role';
import { computed, ComputedRef } from 'vue';

interface UseRole {
  isAdmin: ComputedRef<boolean>;
  isMember: ComputedRef<boolean>;
  isCoach: ComputedRef<boolean>;
}

export function useRole(roles: string[]): UseRole {

  const isAdmin = computed(() => {
    return roles.includes(Role.Admin) ?? false;
  })

  const isMember = computed(() => {
    return roles.includes(Role.Member) ?? false;
  })

  const isCoach = computed(() => {
    return roles.includes(Role.Coach) ?? false;
  })

  return {
    isAdmin,
    isMember,
    isCoach
  }
}
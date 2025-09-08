import { Role } from '@/types/enums/Role';

interface UseRole {
  isAdmin: (roles: string[]) => boolean;
  isMember: (roles: string[]) => boolean;
  isCoach: (roles: string[]) => boolean;
}

export function useRole(): UseRole {

  const isAdmin = (roles: string[]) => {
    return roles.includes(Role.Admin) ?? false;
  }

  const isMember = (roles: string[]) => {
    return roles.includes(Role.Member) ?? false;
  }

  const isCoach = (roles: string[]) => {
    return roles.includes(Role.Coach) ?? false;
  }

  return {
    isAdmin,
    isMember,
    isCoach
  }
}
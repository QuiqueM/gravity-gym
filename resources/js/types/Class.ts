import type { Coach } from './Coach';

export interface TypeClass {
  id: number;
  name: string;
  description: string;
}

export interface CreateClassProps {
  types: TypeClass[];
  coaches: Coach[];
}
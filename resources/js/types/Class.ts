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

export interface Classes {
  capacity: number;
  class_type_id: number;
  id: number;
  instructor: Coach;
  instructor_id: number;
  requires_membership: boolean;
  title: string;
  type: TypeClass;
}
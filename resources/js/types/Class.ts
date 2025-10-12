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

export interface Registration {
  id: number;
  user_id: number;
  class_schedule_id: number;
  status: string;
  created_at: string;
  updated_at: string;
  class_schedule: {
    id: number;
    class_id: number;
    starts_at: string;
    ends_at: string;
    created_at: string;
    updated_at: string;
    class: Classes;
    room: string;
  };
}
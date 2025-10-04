import type { Coach } from './Coach';
import type { TypeClass } from './Class';

export interface ScheduleProps {
  classSelected: {
    id: number;
    title: string;
    capacity: number;
    requires_membership: boolean;
    type: TypeClass;
    instructor: Coach;
  };
}

export interface ScheduleForm {
  class_id: number | null;
  starts_at: string;
  ends_at: string;
  room: string;
  repeat: boolean;
  repeat_days: string[];
  repeat_months: number;
}

export interface ScheduleWithClass {
  id: number;
  class_id: number;
  starts_at: string;
  ends_at: string;
  room: string;
  class: {
    id: number;
    title: string;
    capacity: number;
    requires_membership: boolean;
    type: TypeClass;
    instructor: Coach;
  };
}
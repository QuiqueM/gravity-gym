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
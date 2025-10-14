export interface Event {
  id: number;
  title: string;
  description: string;
  image?: string;
  created_at: string;
  updated_at: string;
  class_id: number;
  gym_class: {
    capacity: number;
    class_type_id: number;
    id: number;
    instructor_id: number;
    requires_membership: boolean;
    title: string;
  }
}
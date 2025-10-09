export interface Plan {
  id: number;
  name: string;
  description?: string;
  duration_days: number;
  class_limit?: number | null;
  price: number;
  is_active: boolean;
}

export interface MembershipItem {
  id: number;
  user: { id: number; name: string };
  plan: { id: number; name: string };
  starts_at: string;
  ends_at: string;
  status: string;
}

export interface Membership {
  id: number;
  plan: Plan;
  starts_at: string;
  ends_at: string;
  status: string;
  remaining_classes: number;
}
export interface Plan {
  id: number;
  name: string;
  description?: string;
  duration_days: number;
  class_limit_per_week?: number | null;
  price: string | number;
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
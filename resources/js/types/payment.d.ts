export interface Payment {
  id: number;
  user_id: number;
  membership_plan_id: number;
  amount: number;
  currency: string;
  payment_method: string;
  status: 'approved' | 'pending' | 'rejected' | 'refunded' | 'cancelled' | 'in_process';
  external_id: string;
  external_reference: string;
  external_status: string;
  processed_at: string | null;
  metadata: Record<string, any> | null;
  created_at: string;
  updated_at: string;
  user?: {
    id: number;
    name: string;
    email: string;
  };
  membership_plan?: {
    id: number;
    name: string;
    description: string;
  };
}

export interface PaymentStats {
  total_payments: number;
  approved_payments: number;
  pending_payments: number;
  failed_payments: number;
  total_revenue: number;
  monthly_revenue: number;
}

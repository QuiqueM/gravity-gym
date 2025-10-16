import type { User } from './index';
export interface Comment {
  id: number;
  user_id: number;
  rating: number;
  comment: string;
  created_at: string;
  updated_at: string;
}

export interface CommentWithUser extends Comment {
  user: User;
};
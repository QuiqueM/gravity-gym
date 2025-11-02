export interface Category {
  id: number;
  name: string;
}

export interface Exercise {
  id: number;
  name: string;
  description: string | null;
  demonstration_video: string | null;
  category_id: number;
  created_at: string;
  updated_at: string;
}

export interface ExerciseWithCategory {
    id: number;
    name: string;
    description: string | null;
    demonstration_video: string | null;
    category: Category;
    created_at: string;
    updated_at: string;
}

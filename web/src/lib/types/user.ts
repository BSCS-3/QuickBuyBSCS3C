export interface User {
  id: number;
  username: string;
  email: string;
  first_name: string | null;
  last_name: string | null;
  role: 'customer' | 'seller' | 'admin';
  is_active: boolean;
  created_at?: string;
  updated_at?: string;
} 
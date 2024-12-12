export interface Category {
  id: number;
  name: string;
  description?: string;
  status: 'active' | 'inactive';
  product_count: number;
  created_at: string;
  updated_at: string;
}

export interface Product {
  id: number;
  name: string;
  description?: string;
  price: number;
  stock: number;
  category?: string;
  image_url?: string;
  status: 'active' | 'inactive';
  created_at: string;
  updated_at: string;
} 
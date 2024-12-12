const API_BASE = '/api';

interface LoginData {
  username: string;
  password: string;
}

interface ApiResponse<T> {
  success: boolean;
  data?: T;
  error?: string;
}

interface RegisterData {
  username: string;
  password: string;
  confirmPassword: string;
}

interface ProductData {
  id?: number;
  name: string;
  description?: string;
  price: number;
  stock: number;
  category?: string;
  image_url?: string;
  user_id?: number;
}

export interface DashboardData {
  totalProducts: number;
  totalCategories: number;
  lowStockItems: number;
}

interface Category {
  id: number;
  name: string;
  description?: string;
  status: 'active' | 'inactive';
  product_count: number;
  created_at: string;
  updated_at: string;
}

interface User {
  id: number;
  username: string;
  role: string;
  created_at: string;
  updated_at: string;
}

export async function login(data: LoginData) {
  const response = await fetch(`${API_BASE}/login`, {
    method: 'POST',
    headers: {
      'Content-Type': 'application/json',
    },
    body: JSON.stringify(data),
  });
  return response.json();
}

export async function getProducts(): Promise<ProductData[]> {
  try {
    const response = await fetch(`${API_BASE}/products`);
    const result = await response.json();
    
    if (!response.ok) {
      throw new Error(result.message || 'Failed to fetch products');
    }
    
    return result;
  } catch (error) {
    console.error('Products error:', error);
    throw error;
  }
}

export async function createProduct(data: ProductData) {
  try {
    console.log('Creating product:', data);
    const response = await fetch(`${API_BASE}/products`, {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
      },
      body: JSON.stringify(data),
    });

    console.log('Create product response status:', response.status);
    const result = await response.json();
    console.log('Create product response:', result);

    if (!response.ok) {
      throw new Error(result.message || 'Failed to create product');
    }

    return result;
  } catch (error) {
    console.error('Create product error:', error);
    throw error;
  }
}

export async function getUsers(): Promise<User[]> {
  try {
    const response = await fetch(`${API_BASE}/users`);
    const result = await response.json();
    
    if (!response.ok) {
      throw new Error(result.message || 'Failed to fetch users');
    }
    
    return result;
  } catch (error) {
    console.error('Users error:', error);
    throw error;
  }
}

export async function createUser(data: { username: string; password: string; role: string }) {
  try {
    const response = await fetch(`${API_BASE}/users`, {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
      },
      body: JSON.stringify(data),
    });

    const result = await response.json();
    
    if (!response.ok) {
      throw new Error(result.message || 'Failed to create user');
    }
    
    return result;
  } catch (error) {
    console.error('Create user error:', error);
    throw error;
  }
}

export async function register(data: RegisterData) {
  try {
    console.log('Sending register request to:', `${API_BASE}/register`);
    const response = await fetch(`${API_BASE}/register`, {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
      },
      body: JSON.stringify({
        username: data.username,
        password: data.password
      }),
    });

    console.log('Register response status:', response.status);
    const result = await response.json();
    console.log('Register response data:', result);
    
    if (!response.ok) {
      throw new Error(result.message || 'Registration failed');
    }
    
    return result;
  } catch (error) {
    console.error('Registration error:', error);
    throw error;
  }
}

export async function getProduct(id: number) {
  const response = await fetch(`${API_BASE}/product?id=${id}`);
  return response.json();
}

export async function updateProduct(id: number, data: ProductData) {
  try {
    console.log('Updating product:', { id, data });
    const response = await fetch(`${API_BASE}/product`, {
      method: 'PUT',
      headers: {
        'Content-Type': 'application/json',
      },
      body: JSON.stringify({ id, ...data }),
    });

    console.log('Update product response status:', response.status);
    const result = await response.json();
    console.log('Update product response:', result);

    if (!response.ok) {
      throw new Error(result.message || 'Failed to update product');
    }

    return result;
  } catch (error) {
    console.error('Update product error:', error);
    throw error;
  }
}

export async function deleteProduct(id: number) {
  try {
    console.log('Deleting product:', id);
    const response = await fetch(`${API_BASE}/products?id=${id}`, {
      method: 'DELETE',
      headers: {
        'Content-Type': 'application/json',
        'Accept': 'application/json'
      }
    });

    console.log('Delete product response status:', response.status);
    const result = await response.json();
    console.log('Delete product response:', result);

    if (!response.ok) {
      throw new Error(result.message || 'Failed to delete product');
    }

    return result;
  } catch (error) {
    console.error('Delete product error:', error);
    throw error;
  }
}

export async function updateProductStock(id: number, stockChange: number, reason: string, userId?: number) {
  return updateProduct(id, {
    stock_change: stockChange,
    reason,
    user_id: userId
  });
}

export async function getDashboardData(): Promise<DashboardData> {
  try {
    const response = await fetch(`${API_BASE}/dashboard`);
    const result = await response.json();
    
    if (!response.ok) {
      throw new Error(result.message || 'Failed to fetch dashboard data');
    }
    
    return result.data;
  } catch (error) {
    console.error('Dashboard data error:', error);
    throw error;
  }
}

export async function getCategories(): Promise<Category[]> {
  try {
    const response = await fetch(`${API_BASE}/categories`);
    const result = await response.json();
    
    if (!response.ok) {
      throw new Error(result.message || 'Failed to fetch categories');
    }
    
    return result;
  } catch (error) {
    console.error('Categories error:', error);
    throw error;
  }
}

export async function createCategory(data: { name: string; description?: string }) {
  try {
    const response = await fetch(`${API_BASE}/categories`, {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
      },
      body: JSON.stringify(data),
    });

    const result = await response.json();
    
    if (!response.ok) {
      throw new Error(result.message || 'Failed to create category');
    }
    
    return result;
  } catch (error) {
    console.error('Create category error:', error);
    throw error;
  }
}

export async function deleteUser(id: number) {
  try {
    const response = await fetch(`${API_BASE}/users?id=${id}`, {
      method: 'DELETE',
    });

    const result = await response.json();
    
    if (!response.ok) {
      throw new Error(result.message || 'Failed to delete user');
    }
    
    return result;
  } catch (error) {
    console.error('Delete user error:', error);
    throw error;
  }
}

export async function updateUserRole(userId: number, role: 'user' | 'admin') {
  try {
    const response = await fetch(`${API_BASE}/users/${userId}`, {
      method: 'PUT',
      headers: {
        'Content-Type': 'application/json',
      },
      body: JSON.stringify({ role }),
    });

    const result = await response.json();
    
    if (!response.ok) {
      throw new Error(result.message || 'Failed to update user role');
    }
    
    return result;
  } catch (error) {
    console.error('Update user role error:', error);
    throw error;
  }
}

export async function updatePassword(userId: number, currentPassword: string, newPassword: string) {
  try {
    const response = await fetch(`${API_BASE}/account/password`, {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
      },
      body: JSON.stringify({
        userId,
        currentPassword,
        newPassword
      }),
    });

    const result = await response.json();
    
    if (!response.ok) {
      throw new Error(result.message || 'Failed to update password');
    }
    
    return result;
  } catch (error) {
    console.error('Update password error:', error);
    throw error;
  }
} 
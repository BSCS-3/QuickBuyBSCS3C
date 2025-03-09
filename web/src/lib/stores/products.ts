import { writable } from 'svelte/store';
import { api } from '$lib/services/api.js';

interface Product {
  id: number;
  seller_id: number;
  name: string;
  description: string | null;
  price: number;
  stock: number;
  image_url: string | null;
  is_active: boolean;
  created_at: string;
  updated_at: string | null;
}

function createProductStore() {
  const { subscribe, set, update } = writable<{
    products: Product[];
    loading: boolean;
    error: string | null;
  }>({
    products: [],
    loading: false,
    error: null
  });

  return {
    subscribe,

    // Get all products for a seller
    fetchSellerProducts: async (sellerId: number) => {
      update(state => ({ ...state, loading: true, error: null }));
      try {
        const response = await api.get(`seller/products/${sellerId}`);
        if (response.payload) {
          update(state => ({
            ...state,
            products: response.payload,
            loading: false
          }));
        } else {
          throw new Error(response.status?.message || 'Failed to fetch products');
        }
      } catch (err: any) {
        update(state => ({
          ...state,
          error: err.message || 'Error fetching products',
          loading: false
        }));
        throw err;
      }
    },

    // Add a new product
    addProduct: async (productData: Partial<Product>) => {
      update(state => ({ ...state, loading: true, error: null }));
      try {
        const response = await api.post('seller/products/add', productData);
        if (response.payload) {
          update(state => ({
            ...state,
            products: [...state.products, response.payload],
            loading: false
          }));
          return response;
        } else {
          throw new Error(response.status?.message || 'Failed to add product');
        }
      } catch (err: any) {
        update(state => ({
          ...state,
          error: err.message || 'Error adding product',
          loading: false
        }));
        throw err;
      }
    },

    // Update an existing product
    updateProduct: async (productId: number, productData: Partial<Product>) => {
      update(state => ({ ...state, loading: true, error: null }));
      try {
        const response = await api.post(`seller/products/update/${productId}`, productData);
        if (response.payload) {
          update(state => ({
            ...state,
            products: state.products.map(p => 
              p.id === productId ? { ...p, ...response.payload } : p
            ),
            loading: false
          }));
          return response;
        } else {
          throw new Error(response.status?.message || 'Failed to update product');
        }
      } catch (err: any) {
        update(state => ({
          ...state,
          error: err.message || 'Error updating product',
          loading: false
        }));
        throw err;
      }
    },

    // Delete a product
    deleteProduct: async (productId: number) => {
      update(state => ({ ...state, loading: true, error: null }));
      try {
        const response = await api.delete(`seller/products/delete/${productId}`);
        if (response.status?.remarks === 'success') {
          update(state => ({
            ...state,
            products: state.products.filter(p => p.id !== productId),
            loading: false
          }));
          return response;
        } else {
          throw new Error(response.status?.message || 'Failed to delete product');
        }
      } catch (err: any) {
        update(state => ({
          ...state,
          error: err.message || 'Error deleting product',
          loading: false
        }));
        throw err;
      }
    },

    // Reset store state
    reset: () => {
      set({
        products: [],
        loading: false,
        error: null
      });
    },

    // Fetch all active products
    fetchAllProducts: async () => {
      update(state => ({ ...state, loading: true, error: null }));
      try {
        const response = await api.get('products');
        if (response.payload) {
          update(state => ({
            ...state,
            products: response.payload,
            loading: false
          }));
        } else {
          throw new Error(response.status?.message || 'Failed to fetch products');
        }
      } catch (err: any) {
        update(state => ({
          ...state,
          error: err.message || 'Error fetching products',
          loading: false
        }));
        throw err;
      }
    }
  };
}

export const productStore = createProductStore(); 
import { writable } from 'svelte/store';
import { api } from '$lib/services/api.js';
import { auth } from '$lib/stores/auth.js';

interface BusinessProfile {
  seller_id: number;
  business_name: string;
  description: string | null;
  logo_url: string | null;
  contact_email: string;
  contact_phone: string | null;
  address: string | null;
  is_approved: boolean;
  approval_date: string | null;
  created_at: string;
  updated_at: string | null;
}

function createBusinessProfileStore() {
  const { subscribe, set, update } = writable<{
    profile: BusinessProfile | null;
    loading: boolean;
    error: string | null;
  }>({
    profile: null,
    loading: false,
    error: null
  });

  return {
    subscribe,

    // Get business profile
    fetchProfile: async () => {
      update(state => ({ ...state, loading: true, error: null }));
      try {
        const authUser = JSON.parse(localStorage.getItem('user') || '{}');
        if (!authUser.id) {
          throw new Error('User not authenticated');
        }

        const response = await api.get(`seller/business-profile/${authUser.id}`);
        if (response.payload) {
          update(state => ({
            ...state,
            profile: response.payload,
            loading: false
          }));
        } else {
          throw new Error(response.status?.message || 'Failed to fetch business profile');
        }
      } catch (err: any) {
        update(state => ({
          ...state,
          error: err.message || 'Error fetching business profile',
          loading: false
        }));
        throw err;
      }
    },

    // Update business profile
    updateProfile: async (profileData: Partial<BusinessProfile>) => {
      update(state => ({ ...state, loading: true, error: null }));
      try {
        const authUser = JSON.parse(localStorage.getItem('user') || '{}');
        if (!authUser.id) {
          throw new Error('User not authenticated');
        }

        const response = await api.post(`seller/business-profile/${authUser.id}`, profileData);
        if (response.payload) {
          update(state => ({
            ...state,
            profile: { ...state.profile, ...response.payload },
            loading: false
          }));
          return response;
        } else {
          throw new Error(response.status?.message || 'Failed to update business profile');
        }
      } catch (err: any) {
        update(state => ({
          ...state,
          error: err.message || 'Error updating business profile',
          loading: false
        }));
        throw err;
      }
    },

    reset: () => {
      set({
        profile: null,
        loading: false,
        error: null
      });
    }
  };
}

export const businessProfileStore = createBusinessProfileStore(); 
import { writable } from 'svelte/store';
import { api } from '$lib/services/api.js';
import { auth } from '$lib/stores/auth.js';


function createProfileStore() {
  const { subscribe, set, update } = writable<any>({
    profile: null,
    loading: false,
    error: null
  });

  return {
    subscribe,

    // Get user profile
    fetchProfile: async () => {
      update(state => ({ ...state, loading: true, error: null }));
      try {
        // Get authenticated user ID from auth store
        const authUser = JSON.parse(localStorage.getItem('user') || '{}');
        if (!authUser.id) {
          throw new Error('User not authenticated');
        }

        const response = await api.get(`customer/profile/${authUser.id}`);
        if (response.payload) {
          update(state => ({
            ...state,
            profile: response.payload,
            loading: false
          }));
        } else {
          throw new Error(response.status?.message || 'Failed to fetch profile');
        }
      } catch (err: any) {
        update(state => ({
          ...state,
          error: err.message || 'Error fetching profile',
          loading: false
        }));
        throw err;
      }
    },

    // Update user profile
    updateProfile: async (profileData: Partial<any>) => {
      update(state => ({ ...state, loading: true, error: null }));
      try {
        // Get authenticated user ID from auth store
        const authUser = JSON.parse(localStorage.getItem('user') || '{}');
        if (!authUser.id) {
          throw new Error('User not authenticated');
        }

        const response = await api.post(`customer/profile/${authUser.id}`, profileData);
        if (response.payload) {
          update(state => ({
            ...state,
            profile: { ...state.profile, ...response.payload },
            loading: false
          }));
          return response;
        } else {
          throw new Error(response.status?.message || 'Failed to update profile');
        }
      } catch (err: any) {
        update(state => ({
          ...state,
          error: err.message || 'Error updating profile',
          loading: false
        }));
        throw err;
      }
    },

    // Reset store state
    reset: () => {
      set({
        profile: null,
        loading: false,
        error: null
      });
    }
  };
}

export const profileStore = createProfileStore(); 
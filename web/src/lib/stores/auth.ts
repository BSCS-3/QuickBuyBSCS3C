import { writable } from "svelte/store";


function createAuthStore() {
  const { subscribe, set, update } = writable<any>({
    isAuthenticated: false,
    token: null,
    user: null,
  });

  return {
    subscribe,
    
    login: (token: string, user: any) => {
      localStorage.setItem("token", token);
      localStorage.setItem("user", JSON.stringify(user));
      set({ isAuthenticated: true, token, user });
    },
    logout: () => {
      localStorage.removeItem("token");
      localStorage.removeItem("user");
      set({ isAuthenticated: false, token: null, user: null });
    },
    initialize: () => {
      const token = localStorage.getItem("token");
      const user = JSON.parse(localStorage.getItem("user") || "null");
      if (token && user) {
        set({ isAuthenticated: true, token, user });
      }
    },
  };
}

export const auth = createAuthStore();

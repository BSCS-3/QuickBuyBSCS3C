import { writable } from 'svelte/store';
import { api } from '$lib/services/api.js';

function createUsersStore() {
    const { subscribe, set, update } = writable<any[]>([]);

    return {
        subscribe,
        
        loadUsers: async () => {
            try {
                let endpoint = "admin/users";

                // api request.
                const response = await api.get(endpoint);

                console.log(response);

                set(response);
            } catch (err: any) {
                console.error('Error loading users:', err);
                throw err;
            }
        },

        deleteUser: async (userId: string) => {
            try {
                // api request.
                await api.delete(`admin/deleteuser/${userId}`);

                // reload store after magdelete.
                update(users => users.filter(user => user.id !== userId));
            } catch (err: any) {
                console.error('Error deleting user:', err);
                throw err;
            }
        },
        reset: () => set([])
    };
}

export const UsersStore = createUsersStore();

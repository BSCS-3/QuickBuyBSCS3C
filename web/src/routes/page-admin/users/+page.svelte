<script lang="ts">
    import { UsersStore } from "$lib/stores/user.js";
    import { onMount } from "svelte";

    // initial loading states.
    let loading = true;
    let deleteLoading = false;

    onMount(async () => {
        try {
            await UsersStore.loadUsers();
        } finally {
            // stop loading.
            loading = false;
        }
    });

    async function handleDelete(userId: string) {
        //loading.
        deleteLoading = true;
        try {
            await UsersStore.deleteUser(userId);
        } catch (error) {
            alert("Failed to delete user");
        } finally {
            // stop loading
            deleteLoading = false;
        }
    }

    // filtered users from all users fetched from store.
    $: customers = $UsersStore.filter((user) => user.role === "customer");
    $: sellers = $UsersStore.filter((user) => user.role === "seller");
</script>

<h1>Users</h1>

{#if loading}
    <div>Loading users...</div>
{:else}
    <div class="grid grid-cols-1 md:grid-cols-2 gap-8 p-4">
        <!-- customers only. -->
        <div>
            <h2>Customers ({customers.length})</h2>
            {#if customers.length === 0}
                <p>No customers found</p>
            {:else}
                {#each customers as user}
                    <!-- user info. -->
                    <div>
                        <h3>{user.username}</h3>
                        <p>Email: {user.email}</p>
                        <p>Created: {user.created_at}</p>
                    </div>

                    <!-- delete button. -->
                    <button
                        on:click={() => handleDelete(user._id)}
                        class="bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded-md text-sm transition-colors"
                        disabled={deleteLoading}
                    >
                        {deleteLoading ? "Deleting..." : "Delete"}
                    </button>
                {/each}
            {/if}
        </div>

        <!-- sellers only. -->
        <div>
            <h2>Sellers ({sellers.length})</h2>
            {#if sellers.length === 0}
                <p>No sellers found</p>
            {:else}
                {#each sellers as user}
                    <!-- user info. -->
                    <div>
                        <h3>{user.username}</h3>
                        <p>Email: {user.email}</p>
                        <p>Created: {user.created_at}</p>
                    </div>

                    <!-- delete button. -->
                    <button
                        on:click={() => handleDelete(user.id)}
                        class="bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded-md text-sm transition-colors"
                        disabled={deleteLoading}
                    >
                        {deleteLoading ? "Deleting..." : "Delete"}
                    </button>
                {/each}
            {/if}
        </div>
    </div>
{/if}

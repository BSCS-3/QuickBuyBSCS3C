<script lang="ts">
  import { goto } from "$app/navigation";
  import { auth } from "$lib/stores/auth.js";
  
  let user: any = null;
  let isMenuOpen = false;

  // Subscribe to auth store to get user info
  auth.subscribe(state => {
    user = state.user;
  });

  async function handleLogout() {
    try {
      await auth.logout();
      goto("/login");
    } catch (err) {
      console.error("Logout failed:", err);
    }
  }

  function toggleMenu() {
    isMenuOpen = !isMenuOpen;
  }
</script>

<header class="bg-white shadow-md">
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="flex justify-between items-center h-16">
      <!-- Logo -->
      <div class="flex-shrink-0">
        <h1 class="text-[#21463E] font-extrabold text-3xl">
          Quick<span class="text-yellow-500">Buy</span>
        </h1>
      </div>

      <!-- Navigation -->
      {#if user}
        <nav class="hidden md:flex space-x-8">
          {#if user.role === 'customer'}
            <a 
              href="/page-customer/browse" 
              class="text-gray-700 hover:text-[#21463E] px-3 py-2 rounded-md text-sm font-medium"
            >
              Browse
            </a>
            <a 
              href="/page-customer/profile" 
              class="text-gray-700 hover:text-[#21463E] px-3 py-2 rounded-md text-sm font-medium"
            >
              Profile
            </a>
          {:else if user.role === 'seller'}
            <a 
              href="/page-seller/products" 
              class="text-gray-700 hover:text-[#21463E] px-3 py-2 rounded-md text-sm font-medium"
            >
              Your Products
            </a>
            <a 
              href="/page-seller/profile" 
              class="text-gray-700 hover:text-[#21463E] px-3 py-2 rounded-md text-sm font-medium"
            >
              Profile
            </a>
          {:else if user.role === 'admin'}
            <a 
              href="/page-admin/users" 
              class="text-gray-700 hover:text-[#21463E] px-3 py-2 rounded-md text-sm font-medium"
            >
              Users
            </a>
            <a 
              href="/page-admin/dashboard" 
              class="text-gray-700 hover:text-[#21463E] px-3 py-2 rounded-md text-sm font-medium"
            >
              Dashboard
            </a>
          {/if}
        </nav>
      {/if}

      <!-- User Menu -->
      <div class="flex items-center">
        {#if user}
          <div class="relative ml-3">
            <button
              type="button"
              class="flex items-center max-w-xs text-sm focus:outline-none"
              on:click={toggleMenu}
            >
              <span class="sr-only">Open user menu</span>
              <div class="h-8 w-8 rounded-full bg-[#21463E] text-white flex items-center justify-center">
                {user.username?.[0]?.toUpperCase() || 'U'}
              </div>
              <span class="ml-2 text-gray-700">{user.username}</span>
            </button>

            {#if isMenuOpen}
              <!-- svelte-ignore a11y_click_events_have_key_events -->
              <!-- svelte-ignore a11y_no_static_element_interactions -->
              <div
                class="origin-top-right absolute right-0 mt-2 w-48 rounded-md shadow-lg py-1 bg-white ring-1 ring-black ring-opacity-5"
                on:click={() => (isMenuOpen = false)}
              >
                <button
                  on:click={handleLogout}
                  class="w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100"
                >
                  Sign out
                </button>
              </div>
            {/if}
          </div>
        {:else}
          <a
            href="/login"
            class="text-gray-700 hover:text-[#21463E] px-3 py-2 rounded-md text-sm font-medium"
          >
            Sign in
          </a>
        {/if}
      </div>
    </div>
  </div>
</header>

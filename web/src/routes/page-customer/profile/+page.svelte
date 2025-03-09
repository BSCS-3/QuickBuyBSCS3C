<script lang="ts">
  import { onMount } from 'svelte';
  import { profileStore } from '$lib/stores/profile.js';
  import { auth } from '$lib/stores/auth.js';

  let profile:any = null;
  let loading = false;
  let error:any = null;
  let isEditing = false;
  let successMessage = '';

  // Form data
  let formData = {
    username: '',
    email: '',
    first_name: '',
    last_name: ''
  };

  // Subscribe to profile store
  profileStore.subscribe(state => {
    profile = state.profile;
    loading = state.loading;
    error = state.error;
    
    if (profile) {
      formData = {
        username: profile.username || '',
        email: profile.email || '',
        first_name: profile.first_name || '',
        last_name: profile.last_name || ''
      };
    }
  });

  onMount(async () => {
    try {
      await profileStore.fetchProfile();
    } catch (err) {
      console.error('Failed to load profile:', err);
    }
  });

  async function handleSubmit() {
    try {
      const response = await profileStore.updateProfile(formData);
      if (response.status.remarks === 'success') {
        successMessage = 'Profile updated successfully!';
        isEditing = false;
        setTimeout(() => {
          successMessage = '';
        }, 3000);
      }
    } catch (err) {
      console.error('Failed to update profile:', err);
    }
  }
</script>

<div class="min-h-screen  py-8 px-4">
  <div class="max-w-3xl mx-auto">
    <!-- Header -->
    <div class="bg-white rounded-lg shadow-sm p-6 mb-6">
      <h1 class="text-2xl font-bold text-[#21463E] mb-2">My Profile</h1>
      <p class="text-gray-600">Manage your account information</p>
    </div>

    <!-- Main Content -->
    <div class="bg-white rounded-lg shadow-sm p-6">
      {#if loading}
        <div class="flex justify-center items-center h-48">
          <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-[#21463E]"></div>
        </div>
      {:else if error}
        <div class="bg-red-100 text-red-700 p-4 rounded-lg mb-4">
          {error}
        </div>
      {:else if profile}
        {#if successMessage}
          <div class="bg-green-100 text-green-700 p-4 rounded-lg mb-4 transition-all">
            {successMessage}
          </div>
        {/if}

        <form on:submit|preventDefault={handleSubmit} class="space-y-6">
          <!-- Profile Information -->
          <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Username -->
            <div>
              <label for="username" class="block text-sm font-medium text-gray-700 mb-1">
                Username
              </label>
              <input
                type="text"
                id="username"
                bind:value={formData.username}
                disabled={!isEditing}
                class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-[#21463E] focus:border-transparent
                      {isEditing ? 'bg-white' : 'bg-gray-50'}"
              />
            </div>

            <!-- Email -->
            <div>
              <label for="email" class="block text-sm font-medium text-gray-700 mb-1">
                Email
              </label>
              <input
                type="email"
                id="email"
                bind:value={formData.email}
                disabled={!isEditing}
                class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-[#21463E] focus:border-transparent
                      {isEditing ? 'bg-white' : 'bg-gray-50'}"
              />
            </div>

            <!-- First Name -->
            <div>
              <label for="firstName" class="block text-sm font-medium text-gray-700 mb-1">
                First Name
              </label>
              <input
                type="text"
                id="firstName"
                bind:value={formData.first_name}
                disabled={!isEditing}
                class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-[#21463E] focus:border-transparent
                      {isEditing ? 'bg-white' : 'bg-gray-50'}"
              />
            </div>

            <!-- Last Name -->
            <div>
              <label for="lastName" class="block text-sm font-medium text-gray-700 mb-1">
                Last Name
              </label>
              <input
                type="text"
                id="lastName"
                bind:value={formData.last_name}
                disabled={!isEditing}
                class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-[#21463E] focus:border-transparent
                      {isEditing ? 'bg-white' : 'bg-gray-50'}"
              />
            </div>
          </div>

          <!-- Account Information -->
          <div class="border-t pt-6">
            <h2 class="text-lg font-medium text-gray-900 mb-4">Account Information</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
              <div>
                <span class="text-gray-500">Account Type:</span>
                <span class="ml-2 text-gray-900 capitalize">{profile.role}</span>
              </div>
              <div>
                <span class="text-gray-500">Status:</span>
                <span class="ml-2 text-gray-900">
                  {profile.is_active ? 'Active' : 'Inactive'}
                </span>
              </div>
              {#if profile.created_at}
                <div>
                  <span class="text-gray-500">Member Since:</span>
                  <span class="ml-2 text-gray-900">
                    {new Date(profile.created_at).toLocaleDateString()}
                  </span>
                </div>
              {/if}
            </div>
          </div>

          <!-- Action Buttons -->
          <div class="flex justify-end space-x-4 pt-4 border-t">
            {#if isEditing}
              <button
                type="button"
                on:click={() => {
                  isEditing = false;
                  if (profile) {
                    formData = {
                      username: profile.username || '',
                      email: profile.email || '',
                      first_name: profile.first_name || '',
                      last_name: profile.last_name || ''
                    };
                  }
                }}
                class="px-4 py-2 text-gray-700 border border-gray-300 rounded-lg hover:bg-gray-50"
              >
                Cancel
              </button>
              <button
                type="submit"
                class="px-4 py-2 bg-[#21463E] text-white rounded-lg hover:bg-[#2d5d52] transition-colors"
              >
                Save Changes
              </button>
            {:else}
              <button
                type="button"
                on:click={() => (isEditing = true)}
                class="px-4 py-2 bg-[#21463E] text-white rounded-lg hover:bg-[#2d5d52] transition-colors"
              >
                Edit Profile
              </button>
            {/if}
          </div>
        </form>
      {/if}
    </div>
  </div>
</div>
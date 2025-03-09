<script lang="ts">
  import { onMount } from 'svelte';
  import { profileStore } from '$lib/stores/profile.js';
  import { businessProfileStore } from '$lib/stores/businessProfile.js';
  import { auth } from '$lib/stores/auth.js';

  let profile: any = null;
  let businessProfile: any = null;
  let loading = false;
  let error: any = null;
  let isEditing = false;
  let successMessage = '';

  // Form data for both personal and business profiles
  let formData = {
    // Personal info
    username: '',
    email: '',
    first_name: '',
    last_name: '',
    // Business info
    business_name: '',
    description: '',
    contact_email: '',
    contact_phone: '',
    address: ''
  };

  // Subscribe to both stores
  profileStore.subscribe(state => {
    profile = state.profile;
    loading = state.loading;
    error = state.error;
    
    if (profile) {
      formData = {
        ...formData,
        username: profile.username || '',
        email: profile.email || '',
        first_name: profile.first_name || '',
        last_name: profile.last_name || ''
      };
    }
  });

  businessProfileStore.subscribe(state => {
    businessProfile = state.profile;
    loading = loading || state.loading;
    error = error || state.error;
    
    if (businessProfile) {
      formData = {
        ...formData,
        business_name: businessProfile.business_name || '',
        description: businessProfile.description || '',
        contact_email: businessProfile.contact_email || '',
        contact_phone: businessProfile.contact_phone || '',
        address: businessProfile.address || ''
      };
    }
  });

  onMount(async () => {
    try {
      await Promise.all([
        profileStore.fetchProfile(),
        businessProfileStore.fetchProfile()
      ]);
    } catch (err) {
      console.error('Failed to load profiles:', err);
    }
  });

  async function handleSubmit() {
    try {
      const personalData = {
        username: formData.username,
        email: formData.email,
        first_name: formData.first_name,
        last_name: formData.last_name
      };

      const businessData = {
        business_name: formData.business_name,
        description: formData.description,
        contact_email: formData.contact_email,
        contact_phone: formData.contact_phone,
        address: formData.address
      };

      await Promise.all([
        profileStore.updateProfile(personalData),
        businessProfileStore.updateProfile(businessData)
      ]);

      successMessage = 'Profile updated successfully!';
      isEditing = false;
      setTimeout(() => {
        successMessage = '';
      }, 3000);
    } catch (err) {
      console.error('Failed to update profiles:', err);
    }
  }
</script>

<div class="min-h-screen py-8 px-4">
  <div class="max-w-4xl mx-auto">
    <!-- Header -->
    <div class="bg-white rounded-lg shadow-sm p-6 mb-6">
      <h1 class="text-2xl font-bold text-[#21463E] mb-2">Seller Profile</h1>
      <p class="text-gray-600">Manage your personal and business information</p>
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
      {:else if profile && businessProfile}
        {#if successMessage}
          <div class="bg-green-100 text-green-700 p-4 rounded-lg mb-4 transition-all">
            {successMessage}
          </div>
        {/if}

        <form on:submit|preventDefault={handleSubmit} class="space-y-8">
          <!-- Personal Information Section -->
          <div>
            <h2 class="text-xl font-semibold text-gray-900 mb-4">Personal Information</h2>
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
          </div>

          <!-- Business Information Section -->
          <div class="border-t pt-8">
            <h2 class="text-xl font-semibold text-gray-900 mb-4">Business Information</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
              <!-- Business Name -->
              <div class="md:col-span-2">
                <label for="businessName" class="block text-sm font-medium text-gray-700 mb-1">
                  Business Name
                </label>
                <input
                  type="text"
                  id="businessName"
                  bind:value={formData.business_name}
                  disabled={!isEditing}
                  class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-[#21463E] focus:border-transparent
                        {isEditing ? 'bg-white' : 'bg-gray-50'}"
                />
              </div>

              <!-- Business Description -->
              <div class="md:col-span-2">
                <label for="description" class="block text-sm font-medium text-gray-700 mb-1">
                  Business Description
                </label>
                <!-- svelte-ignore element_invalid_self_closing_tag -->
                <textarea
                  id="description"
                  bind:value={formData.description}
                  disabled={!isEditing}
                  rows="3"
                  class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-[#21463E] focus:border-transparent
                        {isEditing ? 'bg-white' : 'bg-gray-50'}"
                />
              </div>

              <!-- Contact Email -->
              <div>
                <label for="contactEmail" class="block text-sm font-medium text-gray-700 mb-1">
                  Contact Email
                </label>
                <input
                  type="email"
                  id="contactEmail"
                  bind:value={formData.contact_email}
                  disabled={!isEditing}
                  class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-[#21463E] focus:border-transparent
                        {isEditing ? 'bg-white' : 'bg-gray-50'}"
                />
              </div>

              <!-- Contact Phone -->
              <div>
                <label for="contactPhone" class="block text-sm font-medium text-gray-700 mb-1">
                  Contact Phone
                </label>
                <input
                  type="tel"
                  id="contactPhone"
                  bind:value={formData.contact_phone}
                  disabled={!isEditing}
                  class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-[#21463E] focus:border-transparent
                        {isEditing ? 'bg-white' : 'bg-gray-50'}"
                />
              </div>

              <!-- Business Address -->
              <div class="md:col-span-2">
                <label for="address" class="block text-sm font-medium text-gray-700 mb-1">
                  Business Address
                </label>
                <!-- svelte-ignore element_invalid_self_closing_tag -->
                <textarea
                  id="address"
                  bind:value={formData.address}
                  disabled={!isEditing}
                  rows="2"
                  class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-[#21463E] focus:border-transparent
                        {isEditing ? 'bg-white' : 'bg-gray-50'}"
                />
              </div>
            </div>
          </div>

          <!-- Account Status Section -->
          <div class="border-t pt-6">
            <h2 class="text-lg font-medium text-gray-900 mb-4">Account Status</h2>
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
              <div>
                <span class="text-gray-500">Business Approval:</span>
                <span class="ml-2 text-gray-900">
                  {businessProfile.is_approved ? 'Approved' : 'Pending Approval'}
                </span>
              </div>
              {#if businessProfile.approval_date}
                <div>
                  <span class="text-gray-500">Approval Date:</span>
                  <span class="ml-2 text-gray-900">
                    {new Date(businessProfile.approval_date).toLocaleDateString()}
                  </span>
                </div>
              {/if}
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
                  if (profile && businessProfile) {
                    formData = {
                      username: profile.username || '',
                      email: profile.email || '',
                      first_name: profile.first_name || '',
                      last_name: profile.last_name || '',
                      business_name: businessProfile.business_name || '',
                      description: businessProfile.description || '',
                      contact_email: businessProfile.contact_email || '',
                      contact_phone: businessProfile.contact_phone || '',
                      address: businessProfile.address || ''
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

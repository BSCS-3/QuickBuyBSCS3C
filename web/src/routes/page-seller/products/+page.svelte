<script lang="ts">
  import { onMount } from 'svelte';
  import { productStore } from '$lib/stores/products.js';
  import { auth } from '$lib/stores/auth.js';

  let isAddingProduct = false;
  let isEditingProduct: number | null = null;
  let successMessage = '';
  let errorMessage = '';

  // Form data for new/edit product
  let formData = {
    name: '',
    description: '',
    price: '',
    stock: '',
    image_url: '',
    is_active: true
  };

  // Reset form data
  function resetForm() {
    formData = {
      name: '',
      description: '',
      price: '',
      stock: '',
      image_url: '',
      is_active: true
    };
    isAddingProduct = false;
    isEditingProduct = null;
  }

  // Load seller's products
  onMount(async () => {
    try {
      const user = JSON.parse(localStorage.getItem('user') || '{}');
      if (user.id) {
        await productStore.fetchSellerProducts(user.id);
      }
    } catch (err) {
      console.error('Failed to load products:', err);
    }
  });

  // Handle form submission
  async function handleSubmit() {
    try {
      const user = JSON.parse(localStorage.getItem('user') || '{}');
      if (!user.id) throw new Error('User not authenticated');

      const productData = {
        ...formData,
        seller_id: user.id,
        price: parseFloat(formData.price),
        stock: parseInt(formData.stock)
      };

      if (isEditingProduct) {
        await productStore.updateProduct(isEditingProduct, productData);
        successMessage = 'Product updated successfully';
      } else {
        await productStore.addProduct(productData);
        successMessage = 'Product added successfully';
      }

      resetForm();
      setTimeout(() => successMessage = '', 3000);
    } catch (err: any) {
      errorMessage = err.message || 'Failed to save product';
      setTimeout(() => errorMessage = '', 3000);
    }
  }

  // Handle product deletion
  async function handleDelete(productId: number) {
    if (!confirm('Are you sure you want to delete this product?')) return;

    try {
      await productStore.deleteProduct(productId);
      successMessage = 'Product deleted successfully';
      setTimeout(() => successMessage = '', 3000);
    } catch (err: any) {
      errorMessage = err.message || 'Failed to delete product';
      setTimeout(() => errorMessage = '', 3000);
    }
  }

  // Handle edit button click
  function handleEdit(product: any) {
    formData = {
      name: product.name,
      description: product.description || '',
      price: product.price.toString(),
      stock: product.stock.toString(),
      image_url: product.image_url || '',
      is_active: product.is_active
    };
    isEditingProduct = product.id;
    isAddingProduct = true;
  }
</script>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
  <div class="flex justify-between items-center mb-6">
    <h1 class="text-2xl font-bold text-gray-900">My Products</h1>
    <button
      on:click={() => isAddingProduct = true}
      class="bg-[#21463E] text-white px-4 py-2 rounded-lg hover:bg-[#2d5d52] transition-colors"
    >
      Add New Product
    </button>
  </div>

  {#if successMessage}
    <div class="bg-green-100 text-green-700 p-3 rounded-lg mb-4">
      {successMessage}
    </div>
  {/if}

  {#if errorMessage}
    <div class="bg-red-100 text-red-700 p-3 rounded-lg mb-4">
      {errorMessage}
    </div>
  {/if}

  {#if isAddingProduct}
    <div class="bg-white p-6 rounded-lg shadow-md mb-6">
      <h2 class="text-xl font-semibold mb-4">
        {isEditingProduct ? 'Edit Product' : 'Add New Product'}
      </h2>
      <form on:submit|preventDefault={handleSubmit} class="space-y-4">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
          <div>
            <label for="name" class="block text-sm font-medium text-gray-700">Product Name</label>
            <input
              type="text"
              id="name"
              bind:value={formData.name}
              required
              class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-[#21463E] focus:ring-[#21463E]"
            />
          </div>

          <div>
            <label for="price" class="block text-sm font-medium text-gray-700">Price</label>
            <input
              type="number"
              id="price"
              bind:value={formData.price}
              required
              min="0"
              step="0.01"
              class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-[#21463E] focus:ring-[#21463E]"
            />
          </div>

          <div>
            <label for="stock" class="block text-sm font-medium text-gray-700">Stock</label>
            <input
              type="number"
              id="stock"
              bind:value={formData.stock}
              required
              min="0"
              class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-[#21463E] focus:ring-[#21463E]"
            />
          </div>

          <div>
            <label for="image_url" class="block text-sm font-medium text-gray-700">Image URL</label>
            <input
              type="url"
              id="image_url"
              bind:value={formData.image_url}
              class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-[#21463E] focus:ring-[#21463E]"
            />
          </div>

          <div class="md:col-span-2">
            <label for="description" class="block text-sm font-medium text-gray-700">Description</label>
            <textarea
              id="description"
              bind:value={formData.description}
              rows="3"
              class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-[#21463E] focus:ring-[#21463E]"
            ></textarea>
          </div>
        </div>

        <div class="flex justify-end space-x-3">
          <button
            type="button"
            on:click={resetForm}
            class="px-4 py-2 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50"
          >
            Cancel
          </button>
          <button
            type="submit"
            class="px-4 py-2 bg-[#21463E] text-white rounded-md hover:bg-[#2d5d52]"
          >
            {isEditingProduct ? 'Update Product' : 'Add Product'}
          </button>
        </div>
      </form>
    </div>
  {/if}

  <!-- Products Grid -->
  <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
    {#if $productStore.loading}
      <div class="col-span-full text-center py-8">Loading products...</div>
    {:else if $productStore.error}
      <div class="col-span-full text-center py-8 text-red-600">{$productStore.error}</div>
    {:else if $productStore.products.length === 0}
      <div class="col-span-full text-center py-8 text-gray-500">No products found</div>
    {:else}
      {#each $productStore.products as product}
        <div class="bg-white rounded-lg shadow-md overflow-hidden">
          {#if product.image_url}
            <img
              src={product.image_url}
              alt={product.name}
              class="w-full h-48 object-cover"
            />
          {:else}
            <div class="w-full h-48 bg-gray-200 flex items-center justify-center">
              <span class="text-gray-400">No image</span>
            </div>
          {/if}

          <div class="p-4">
            <h3 class="text-lg font-semibold text-gray-900">{product.name}</h3>
            <p class="text-gray-500 text-sm mt-1">
              {product.description || 'No description'}
            </p>
            <div class="mt-2 flex justify-between items-center">
              <span class="text-lg font-bold text-gray-900">₱{product.price}</span>
              <span class="text-sm text-gray-600">Stock: {product.stock}</span>
            </div>

            <div class="mt-4 flex justify-end space-x-2">
              <button
                on:click={() => handleEdit(product)}
                class="px-3 py-1 text-sm text-[#21463E] border border-[#21463E] rounded hover:bg-[#21463E] hover:text-white"
              >
                Edit
              </button>
              <button
                on:click={() => handleDelete(product.id)}
                class="px-3 py-1 text-sm text-red-600 border border-red-600 rounded hover:bg-red-600 hover:text-white"
              >
                Delete
              </button>
            </div>
          </div>
        </div>
      {/each}
    {/if}
  </div>
</div>
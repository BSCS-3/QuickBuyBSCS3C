<script lang="ts">
  // Hardcoded products data
  const products = [
    {
      id: 1,
      name: "Gaming Laptop XR5000",
      description: "High-performance gaming laptop with RGB keyboard and latest graphics",
      price: 45999,
      stock: 5,
      image_url: "https://picsum.photos/400/300",
      category: "electronics"
    },
    {
      id: 2,
      name: "Casual Denim Jacket",
      description: "Classic blue denim jacket, perfect for any casual outfit",
      price: 1299,
      stock: 15,
      image_url: "https://picsum.photos/400/301",
      category: "clothing"
    },
    {
      id: 3,
      name: "Wireless Earbuds Pro",
      description: "Premium wireless earbuds with active noise cancellation",
      price: 3499,
      stock: 8,
      image_url: "https://picsum.photos/400/302",
      category: "electronics"
    },
    {
      id: 4,
      name: "Smart Watch Series X",
      description: "Advanced smartwatch with health monitoring features",
      price: 8999,
      stock: 0,
      image_url: "https://picsum.photos/400/303",
      category: "electronics"
    },
    {
      id: 5,
      name: "Running Shoes Air Max",
      description: "Comfortable running shoes with advanced cushioning",
      price: 2499,
      stock: 20,
      image_url: "https://picsum.photos/400/304",
      category: "clothing"
    },
    {
      id: 6,
      name: "Coffee Maker Deluxe",
      description: "Automatic coffee maker with multiple brewing options",
      price: 4999,
      stock: 3,
      image_url: "https://picsum.photos/400/305",
      category: "electronics"
    }
  ];
</script>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
  <!-- Search and Filters -->
  <div class="bg-white rounded-lg shadow-sm p-4 mb-8">
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
      <!-- Search -->
      <div>
        <label for="search" class="block text-sm font-medium text-gray-700 mb-1">Search</label>
        <input
          type="text"
          id="search"
          placeholder="Search products..."
          class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-[#21463E] focus:border-transparent"
        />
      </div>

      <!-- Category Filter -->
      <div>
        <label for="category" class="block text-sm font-medium text-gray-700 mb-1">Category</label>
        <select
          id="category"
          class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-[#21463E] focus:border-transparent"
        >
          <option value="all">All Categories</option>
          <option value="electronics">Electronics</option>
          <option value="clothing">Clothing</option>
        </select>
      </div>

      <!-- Sort -->
      <div>
        <label for="sort" class="block text-sm font-medium text-gray-700 mb-1">Sort By</label>
        <select
          id="sort"
          class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-[#21463E] focus:border-transparent"
        >
          <option value="newest">Newest First</option>
          <option value="price-low">Price: Low to High</option>
          <option value="price-high">Price: High to Low</option>
        </select>
      </div>
    </div>
  </div>

  <!-- Products Grid -->
  <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-4 gap-6">
    {#each products as product}
      <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition-shadow">
        <!-- Product Image -->
        <img
          src={product.image_url}
          alt={product.name}
          class="w-full h-48 object-cover"
        />

        <!-- Product Details -->
        <div class="p-4">
          <h3 class="text-lg font-semibold text-gray-900 line-clamp-1">{product.name}</h3>
          <p class="text-gray-500 text-sm mt-1 line-clamp-2">
            {product.description}
          </p>
          
          <!-- Price and Stock -->
          <div class="mt-2 flex justify-between items-center">
            <span class="text-lg font-bold text-gray-900">₱{product.price.toLocaleString()}</span>
            <span class="text-sm text-gray-600">
              {product.stock > 0 ? `${product.stock} in stock` : 'Out of stock'}
            </span>
          </div>

          <!-- Add to Cart Button -->
          <button
            class="w-full mt-4 bg-[#21463E] text-white py-2 rounded-lg hover:bg-[#2d5d52] transition-colors
                   disabled:bg-gray-300 disabled:cursor-not-allowed"
            disabled={product.stock <= 0}
          >
            {product.stock > 0 ? 'Add to Cart' : 'Out of Stock'}
          </button>
        </div>
      </div>
    {/each}
  </div>
</div>

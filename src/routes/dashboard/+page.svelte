<script lang="ts">
  import { onMount } from 'svelte';
  import { getProducts } from '$lib';
  import Sidebar from '$lib/Sidebar.svelte';

  // Dashboard data
  let dashboardData = {
    totalProducts: 0,
    categories: 0,
    lowStockItems: 0
  };

  // Loading and error states
  let loading = true;
  let error = '';

  onMount(async () => {
    await loadDashboardData();
  });

  async function loadDashboardData() {
    loading = true;
    error = '';
    
    try {
      // Fetch products data
      const products = await getProducts();
      if (Array.isArray(products)) {
        dashboardData.totalProducts = products.length;
        
        // Count unique categories
        const uniqueCategories = new Set(products.map(p => p.category).filter(Boolean));
        dashboardData.categories = uniqueCategories.size;
        
        // Count low stock items (less than 10)
        dashboardData.lowStockItems = products.filter(p => p.stock < 10).length;
      }
    } catch (e) {
      console.error('Error loading dashboard data:', e);
      error = 'Failed to load dashboard data';
    } finally {
      loading = false;
    }
  }

  async function handleRefresh() {
    await loadDashboardData();
  }
</script>

<div class="flex">
  <Sidebar />
  
  <main class="ml-56 p-6 w-full">
    <div class="flex justify-between items-center mb-6">
      <h1 class="text-2xl font-bold">Dashboard</h1>
      <button 
        class="bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700 disabled:opacity-50"
        on:click={handleRefresh}
        disabled={loading}
      >
        {loading ? 'Refreshing...' : 'Refresh'}
      </button>
    </div>

    {#if error}
      <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
        {error}
      </div>
    {/if}

    {#if loading && !dashboardData.totalProducts}
      <div class="grid grid-cols-4 gap-6 mb-8">
        {#each Array(4) as _}
          <div class="bg-gray-100 animate-pulse h-32 rounded-lg"></div>
        {/each}
      </div>
    {:else}
      <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
        <div class="bg-green-600 text-white p-6 rounded-lg">
          <h3 class="text-lg">Total Products</h3>
          <p class="text-2xl font-bold">{dashboardData.totalProducts}</p>
        </div>
        
        <div class="bg-cyan-500 text-white p-6 rounded-lg">
          <h3 class="text-lg">Categories</h3>
          <p class="text-2xl font-bold">{dashboardData.categories}</p>
        </div>
        
        <div class="bg-yellow-500 text-white p-6 rounded-lg">
          <h3 class="text-lg">Low Stock Items</h3>
          <p class="text-2xl font-bold">{dashboardData.lowStockItems}</p>
        </div>
      </div>

      <!-- Recent Activity -->
      <div class="bg-white rounded-lg shadow p-6">
        <h2 class="text-xl font-bold mb-4">Recent Activity</h2>
        <div class="space-y-4">
          <!-- Add recent activity items here -->
        </div>
      </div>
    {/if}
  </main>
</div> 
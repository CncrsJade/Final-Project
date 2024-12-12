<script lang="ts">
  import { onMount } from 'svelte';
  import { getCategories, createCategory } from '$lib/api';
  import type { Category } from '$lib/types';
  import Sidebar from '$lib/Sidebar.svelte';

  let categories: Category[] = [];
  let loading = false;
  let error = '';
  let showAddModal = false;

  let newCategory = {
    name: '',
    description: ''
  };

  onMount(async () => {
    await loadCategories();
  });

  async function loadCategories() {
    loading = true;
    error = '';
    
    try {
      categories = await getCategories();
    } catch (e: any) {
      error = 'Failed to load categories';
      console.error('Error loading categories:', e);
    } finally {
      loading = false;
    }
  }

  async function handleAdd() {
    loading = true;
    error = '';
    
    try {
      const result = await createCategory(newCategory);
      
      if (result.success) {
        showAddModal = false;
        newCategory = { name: '', description: '' };
        await loadCategories();
      } else {
        error = result.message || 'Failed to create category';
      }
    } catch (e: any) {
      error = e.message || 'An error occurred';
    } finally {
      loading = false;
    }
  }
</script>

<div class="flex">
  <Sidebar />
  
  <main class="ml-56 p-6 w-full">
    <div class="flex justify-between items-center mb-6">
      <h1 class="text-2xl font-bold">Categories</h1>
      <button
        class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700"
        on:click={() => showAddModal = true}
      >
        Add Category
      </button>
    </div>

    {#if error}
      <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
        {error}
      </div>
    {/if}

    {#if loading && !categories.length}
      <div class="flex justify-center items-center h-64">
        <div class="text-gray-500">Loading categories...</div>
      </div>
    {:else}
      <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        {#each categories as category}
          <div class="bg-white rounded-lg shadow p-4">
            <h3 class="text-lg font-semibold">{category.name}</h3>
            {#if category.description}
              <p class="text-gray-600 text-sm mt-1">{category.description}</p>
            {/if}
            <p class="text-gray-600 mt-2">Products: {category.product_count}</p>
          </div>
        {/each}
      </div>
    {/if}
  </main>
</div>

<!-- Add Category Modal -->
{#if showAddModal}
  <div class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center">
    <div class="bg-white p-6 rounded-lg w-96">
      <h2 class="text-xl font-bold mb-4">Add Category</h2>
      
      {#if error}
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
          {error}
        </div>
      {/if}
      
      <form on:submit|preventDefault={handleAdd} class="space-y-4">
        <div>
          <label for="name" class="block text-sm font-medium text-gray-700">Category Name</label>
          <input
            type="text"
            id="name"
            bind:value={newCategory.name}
            disabled={loading}
            class="mt-1 block w-full px-3 py-2 bg-white border border-gray-300 rounded-md"
            required
          />
        </div>
        
        <div>
          <label for="description" class="block text-sm font-medium text-gray-700">Description</label>
          <textarea
            id="description"
            bind:value={newCategory.description}
            disabled={loading}
            class="mt-1 block w-full px-3 py-2 bg-white border border-gray-300 rounded-md"
            rows="3"
          ></textarea>
        </div>
        
        <div class="flex justify-end space-x-3">
          <button
            type="button"
            class="px-4 py-2 text-gray-600 hover:text-gray-800"
            disabled={loading}
            on:click={() => showAddModal = false}
          >
            Cancel
          </button>
          <button
            type="submit"
            class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 disabled:opacity-50"
            disabled={loading}
          >
            {loading ? 'Adding...' : 'Add Category'}
          </button>
        </div>
      </form>
    </div>
  </div>
{/if} 
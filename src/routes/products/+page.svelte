<script lang="ts">
  import { onMount } from 'svelte';
  import { getProducts, createProduct, updateProduct, deleteProduct } from '$lib';
  import Sidebar from '$lib/Sidebar.svelte';
  import { auth } from '$lib';

  let products = [];
  let loading = false;
  let error = '';
  let showAddModal = false;
  let showEditModal = false;
  let selectedProduct = null;
  let viewMode = 'grid'; // 'grid' or 'list'

  let newProduct = {
    name: '',
    description: '',
    price: '',
    stock: '',
    category: '',
    image_url: ''
  };

  onMount(async () => {
    await loadProducts();
  });

  async function loadProducts() {
    loading = true;
    error = '';
    
    try {
      console.log('Fetching products...');
      const data = await getProducts();
      console.log('Products data:', data);
      products = Array.isArray(data) ? data : [];
    } catch (e) {
      error = 'Failed to load products';
      console.error('Error loading products:', e);
    } finally {
      loading = false;
    }
  }

  async function handleAdd() {
    loading = true;
    error = '';
    
    try {
      const productData = {
        ...newProduct,
        price: parseFloat(newProduct.price),
        stock: parseInt(newProduct.stock),
        user_id: $auth?.id
      };

      console.log('Creating product:', productData);
      const result = await createProduct(productData);
      
      if (result.success) {
        showAddModal = false;
        newProduct = {
          name: '',
          description: '',
          price: '',
          stock: '',
          category: '',
          image_url: ''
        };
        await loadProducts();
      } else {
        error = result.message || 'Failed to create product';
      }
    } catch (e) {
      error = e.message || 'An error occurred while creating the product';
      console.error('Create product error:', e);
    } finally {
      loading = false;
    }
  }

  async function handleEdit() {
    loading = true;
    error = '';
    
    try {
      const productData = {
        ...selectedProduct,
        price: parseFloat(selectedProduct.price),
        stock: parseInt(selectedProduct.stock),
        user_id: $auth?.id
      };

      const result = await updateProduct(selectedProduct.id, productData);
      
      if (result.success) {
        showEditModal = false;
        await loadProducts();
      } else {
        error = result.message || 'Failed to update product';
      }
    } catch (e) {
      error = e.message || 'An error occurred while updating the product';
    } finally {
      loading = false;
    }
  }

  async function handleDelete() {
    loading = true;
    error = '';
    
    try {
      const result = await deleteProduct(selectedProduct.id);
      
      if (result.success) {
        showEditModal = false;
        await loadProducts();
      } else {
        error = result.message || 'Failed to delete product';
      }
    } catch (e) {
      error = e.message || 'An error occurred while deleting the product';
    } finally {
      loading = false;
    }
  }
</script>

<div class="flex">
  <Sidebar />
  
  <main class="ml-56 p-6 w-full">
    <div class="flex justify-between items-center mb-6">
      <h1 class="text-2xl font-bold">Products</h1>
      <div class="flex space-x-4">
        <div class="flex space-x-2">
          <button
            class="px-4 py-2 {viewMode === 'grid' ? 'bg-blue-600 text-white' : 'bg-gray-200'} rounded-lg"
            on:click={() => viewMode = 'grid'}
          >
            Grid
          </button>
          <button
            class="px-4 py-2 {viewMode === 'list' ? 'bg-blue-600 text-white' : 'bg-gray-200'} rounded-lg"
            on:click={() => viewMode = 'list'}
          >
            List
          </button>
        </div>
        <button
          class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700"
          on:click={() => showAddModal = true}
        >
          Add Product
        </button>
      </div>
    </div>

    {#if error}
      <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
        {error}
      </div>
    {/if}

    {#if loading && !products.length}
      <div class="flex justify-center items-center h-64">
        <div class="text-gray-500">Loading products...</div>
      </div>
    {:else if !products.length}
      <div class="flex justify-center items-center h-64">
        <div class="text-gray-500">No products found</div>
      </div>
    {:else}
      {#if viewMode === 'grid'}
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
          {#each products as product}
            <div class="bg-white rounded-lg shadow p-4">
              <img
                src={product.image_url || '/placeholder-image.jpg'}
                alt={product.name}
                class="w-full h-48 object-cover rounded-lg mb-4"
              />
              <h3 class="text-lg font-semibold">{product.name}</h3>
              {#if product.description}
                <p class="text-gray-600 text-sm mb-2">{product.description}</p>
              {/if}
              <p class="text-gray-600">Price: ₱{parseFloat(product.price).toFixed(2)}</p>
              <p class="text-gray-600">Stock: {product.stock} pcs</p>
              {#if product.category}
                <p class="text-gray-600">Category: {product.category}</p>
              {/if}
              <div class="mt-4 flex justify-end space-x-2">
                <button
                  class="text-blue-600 hover:text-blue-800"
                  on:click={() => {
                    selectedProduct = { ...product };
                    showEditModal = true;
                  }}
                >
                  Edit
                </button>
                <button
                  class="text-red-600 hover:text-red-800"
                  on:click={() => handleDelete(product.id)}
                >
                  Delete
                </button>
              </div>
            </div>
          {/each}
        </div>
      {:else}
        <div class="bg-white rounded-lg shadow overflow-hidden">
          <table class="min-w-full">
            <thead class="bg-gray-50">
              <tr>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                  Name
                </th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                  Price
                </th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                  Stock
                </th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                  Category
                </th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                  Actions
                </th>
              </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
              {#each products as product}
                <tr>
                  <td class="px-6 py-4 whitespace-nowrap">
                    <div class="flex items-center">
                      <img
                        src={product.image_url || '/placeholder-image.jpg'}
                        alt={product.name}
                        class="h-10 w-10 rounded-full mr-3"
                      />
                      <div>
                        <div>{product.name}</div>
                        {#if product.description}
                          <div class="text-sm text-gray-500">{product.description}</div>
                        {/if}
                      </div>
                    </div>
                  </td>
                  <td class="px-6 py-4 whitespace-nowrap">
                    ₱{parseFloat(product.price).toFixed(2)}
                  </td>
                  <td class="px-6 py-4 whitespace-nowrap">
                    {product.stock} pcs
                  </td>
                  <td class="px-6 py-4 whitespace-nowrap">
                    {product.category || '-'}
                  </td>
                  <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                    <button
                      class="text-blue-600 hover:text-blue-900 mr-4"
                      on:click={() => {
                        selectedProduct = { ...product };
                        showEditModal = true;
                      }}
                    >
                      Edit
                    </button>
                    <button
                      class="text-red-600 hover:text-red-900"
                      on:click={() => handleDelete(product.id)}
                    >
                      Delete
                    </button>
                  </td>
                </tr>
              {/each}
            </tbody>
          </table>
        </div>
      {/if}
    {/if}
  </main>
</div>

<!-- Add/Edit Modals here --> 
<script lang="ts">
  import ProductModal from './ProductModal.svelte';
  
  let isModalOpen = false;
  let modalMode: 'add' | 'edit' = 'add';
  let currentProductData = {
    name: '',
    price: 0,
    stock: 0,
    image: ''
  };

  let searchQuery = '';
  let viewMode: 'grid' | 'list' = 'grid';

  function openAddModal() {
    modalMode = 'add';
    currentProductData = {
      name: '',
      price: 0,
      stock: 0,
      image: ''
    };
    isModalOpen = true;
  }

  function openEditModal(product: any) {
    modalMode = 'edit';
    currentProductData = {
      name: product.name,
      price: product.price,
      stock: product.stock,
      image: product.image
    };
    isModalOpen = true;
  }
</script>

<div class="p-4">
  <div class="flex justify-between items-center mb-6">
    <h1 class="text-2xl font-bold">Products</h1>
    <button 
      class="bg-blue-500 text-white px-4 py-2 rounded-md hover:bg-blue-600"
      on:click={openAddModal}
    >
      + Add Product
    </button>
  </div>

  <div class="flex justify-between mb-4">
    <input
      type="text"
      placeholder="Search products..."
      class="w-full max-w-md px-4 py-2 border rounded-md"
      bind:value={searchQuery}
    />
    <div class="flex gap-2">
      <button 
        class="px-4 py-2 rounded-md {viewMode === 'grid' ? 'bg-gray-700 text-white' : 'bg-gray-200'}"
        on:click={() => viewMode = 'grid'}
      >
        Grid
      </button>
      <button 
        class="px-4 py-2 rounded-md {viewMode === 'list' ? 'bg-gray-700 text-white' : 'bg-gray-200'}"
        on:click={() => viewMode = 'list'}
      >
        List
      </button>
    </div>
  </div>

  {#if viewMode === 'grid'}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
      <!-- Example product cards -->
      <div class="bg-white p-4 rounded-lg shadow">
        <img src="pandesal.jpg" alt="Pandesal" class="w-full h-48 object-cover rounded-md mb-4">
        <h3 class="text-lg font-semibold">Pandesal</h3>
        <p class="text-gray-600">Price: ₱5.00</p>
        <p class="text-gray-600">Stock: 100 pcs</p>
        <div class="mt-4 flex gap-2">
          <button 
            class="flex-1 bg-blue-500 text-white px-4 py-2 rounded-md hover:bg-blue-600"
            on:click={() => openEditModal({
              name: 'Pandesal',
              price: 5.00,
              stock: 100,
              image: 'pandesal.jpg'
            })}
          >
            Edit
          </button>
          <button class="flex-1 bg-red-500 text-white px-4 py-2 rounded-md hover:bg-red-600">
            Delete
          </button>
        </div>
      </div>
      <!-- Add more product cards here -->
    </div>
  {:else}
    <div class="overflow-x-auto">
      <table class="min-w-full bg-white">
        <thead>
          <tr>
            <th class="px-6 py-3 border-b-2 border-gray-200 text-left">Product</th>
            <th class="px-6 py-3 border-b-2 border-gray-200 text-left">Price</th>
            <th class="px-6 py-3 border-b-2 border-gray-200 text-left">Stock</th>
            <th class="px-6 py-3 border-b-2 border-gray-200 text-left">Actions</th>
          </tr>
        </thead>
        <tbody>
          <tr>
            <td class="px-6 py-4 border-b border-gray-200">Pandesal</td>
            <td class="px-6 py-4 border-b border-gray-200">₱5.00</td>
            <td class="px-6 py-4 border-b border-gray-200">100 pcs</td>
            <td class="px-6 py-4 border-b border-gray-200">
              <button 
                class="text-blue-500 hover:text-blue-700 mr-2"
                on:click={() => openEditModal({
                  name: 'Pandesal',
                  price: 5.00,
                  stock: 100,
                  image: 'pandesal.jpg'
                })}
              >
                Edit
              </button>
              <button class="text-red-500 hover:text-red-700">Delete</button>
            </td>
          </tr>
        </tbody>
      </table>
    </div>
  {/if}
</div>

<ProductModal 
  bind:isOpen={isModalOpen}
  mode={modalMode}
  bind:productData={currentProductData}
/> 
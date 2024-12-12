<script lang="ts">
  import Sidebar from '$lib/Sidebar.svelte';

  // Dummy data for products
  let products = [
    { 
      id: 1, 
      name: 'Pandesal', 
      price: 5.00, 
      stock: 100,
      image: '/images/pandesal.jpg'
    },
    { 
      id: 2, 
      name: 'Ensaymada', 
      price: 20.00, 
      stock: 50,
      image: '/images/ensaymada.jpg'
    },
    { 
      id: 3, 
      name: 'Chocolate Cake', 
      price: 450.00, 
      stock: 5,
      image: '/images/chocolate-cake.jpg'
    }
  ];

  // Modal states
  let showAddModal = false;
  let showEditModal = false;
  let showDeleteModal = false;
  let selectedProduct = null;
  let viewMode = 'grid'; // 'grid' or 'list'

  // New product form data
  let newProduct = {
    name: '',
    price: '',
    stock: '',
    image: ''
  };

  function openAddModal() {
    newProduct = { name: '', price: '', stock: '', image: '' };
    showAddModal = true;
  }

  function openEditModal(product) {
    selectedProduct = { ...product };
    showEditModal = true;
  }

  function openDeleteModal(product) {
    selectedProduct = product;
    showDeleteModal = true;
  }

  function handleAdd() {
    // Add product logic here
    console.log('Adding product:', newProduct);
    showAddModal = false;
  }

  function handleEdit() {
    // Edit product logic here
    console.log('Editing product:', selectedProduct);
    showEditModal = false;
  }

  function handleDelete() {
    // Delete product logic here
    console.log('Deleting product:', selectedProduct);
    showDeleteModal = false;
  }
</script>

<div class="flex">
  <Sidebar />
  
  <main class="ml-56 p-6 w-full">
    <div class="flex justify-between items-center mb-6">
      <h1 class="text-2xl font-bold">Products</h1>
      <div class="flex gap-4">
        <button
          class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 flex items-center gap-2"
          on:click={openAddModal}
        >
          <span>+</span> Add Product
        </button>
        <div class="flex rounded-lg border border-gray-300">
          <button
            class="px-3 py-1 {viewMode === 'grid' ? 'bg-gray-100' : ''}"
            on:click={() => viewMode = 'grid'}
          >
            Grid
          </button>
          <button
            class="px-3 py-1 {viewMode === 'list' ? 'bg-gray-100' : ''}"
            on:click={() => viewMode = 'list'}
          >
            List
          </button>
        </div>
      </div>
    </div>

    <div class="mb-6">
      <input
        type="text"
        placeholder="Search products..."
        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
      />
    </div>

    {#if viewMode === 'grid'}
      <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        {#each products as product}
          <div class="bg-white rounded-lg shadow p-4">
            <img
              src={product.image}
              alt={product.name}
              class="w-full h-48 object-cover rounded-lg mb-4"
            />
            <h3 class="text-lg font-semibold">{product.name}</h3>
            <p class="text-gray-600">Price: ₱{product.price.toFixed(2)}</p>
            <p class="text-gray-600">Stock: {product.stock} pcs</p>
            <div class="mt-4 flex justify-end space-x-2">
              <button
                class="text-blue-600 hover:text-blue-800"
                on:click={() => openEditModal(product)}
              >
                Edit
              </button>
              <button
                class="text-red-600 hover:text-red-800"
                on:click={() => openDeleteModal(product)}
              >
                Delete
              </button>
            </div>
          </div>
        {/each}
      </div>
    {:else}
      <div class="bg-white rounded-lg shadow">
        <table class="w-full">
          <thead>
            <tr class="border-b">
              <th class="text-left p-4">Image</th>
              <th class="text-left p-4">Name</th>
              <th class="text-left p-4">Price</th>
              <th class="text-left p-4">Stock</th>
              <th class="text-left p-4">Actions</th>
            </tr>
          </thead>
          <tbody>
            {#each products as product}
              <tr class="border-b">
                <td class="p-4">
                  <img
                    src={product.image}
                    alt={product.name}
                    class="w-16 h-16 object-cover rounded"
                  />
                </td>
                <td class="p-4">{product.name}</td>
                <td class="p-4">₱{product.price.toFixed(2)}</td>
                <td class="p-4">{product.stock} pcs</td>
                <td class="p-4">
                  <button
                    class="text-blue-600 hover:text-blue-800 mr-2"
                    on:click={() => openEditModal(product)}
                  >
                    Edit
                  </button>
                  <button
                    class="text-red-600 hover:text-red-800"
                    on:click={() => openDeleteModal(product)}
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
  </main>
</div>

<!-- Add Product Modal -->
{#if showAddModal}
  <div class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center">
    <div class="bg-white p-6 rounded-lg w-96">
      <h2 class="text-xl font-bold mb-4">Add Product</h2>
      <form on:submit|preventDefault={handleAdd} class="space-y-4">
        <div>
          <label for="name" class="block text-sm font-medium text-gray-700">Product Name</label>
          <input
            type="text"
            id="name"
            bind:value={newProduct.name}
            class="mt-1 block w-full px-3 py-2 bg-white border border-gray-300 rounded-md"
            required
          />
        </div>
        <div>
          <label for="price" class="block text-sm font-medium text-gray-700">Price</label>
          <input
            type="number"
            id="price"
            bind:value={newProduct.price}
            step="0.01"
            class="mt-1 block w-full px-3 py-2 bg-white border border-gray-300 rounded-md"
            required
          />
        </div>
        <div>
          <label for="stock" class="block text-sm font-medium text-gray-700">Stock</label>
          <input
            type="number"
            id="stock"
            bind:value={newProduct.stock}
            class="mt-1 block w-full px-3 py-2 bg-white border border-gray-300 rounded-md"
            required
          />
        </div>
        <div>
          <label for="image" class="block text-sm font-medium text-gray-700">Image URL</label>
          <input
            type="text"
            id="image"
            bind:value={newProduct.image}
            class="mt-1 block w-full px-3 py-2 bg-white border border-gray-300 rounded-md"
            required
          />
        </div>
        <div class="flex justify-end space-x-3">
          <button
            type="button"
            class="px-4 py-2 text-gray-600 hover:text-gray-800"
            on:click={() => showAddModal = false}
          >
            Cancel
          </button>
          <button
            type="submit"
            class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700"
          >
            Add Product
          </button>
        </div>
      </form>
    </div>
  </div>
{/if}

<!-- Edit Product Modal -->
{#if showEditModal && selectedProduct}
  <div class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center">
    <div class="bg-white p-6 rounded-lg w-96">
      <h2 class="text-xl font-bold mb-4">Edit Product</h2>
      <form on:submit|preventDefault={handleEdit} class="space-y-4">
        <div>
          <label for="edit-name" class="block text-sm font-medium text-gray-700">Product Name</label>
          <input
            type="text"
            id="edit-name"
            bind:value={selectedProduct.name}
            class="mt-1 block w-full px-3 py-2 bg-white border border-gray-300 rounded-md"
            required
          />
        </div>
        <div>
          <label for="edit-price" class="block text-sm font-medium text-gray-700">Price</label>
          <input
            type="number"
            id="edit-price"
            bind:value={selectedProduct.price}
            step="0.01"
            class="mt-1 block w-full px-3 py-2 bg-white border border-gray-300 rounded-md"
            required
          />
        </div>
        <div>
          <label for="edit-stock" class="block text-sm font-medium text-gray-700">Stock</label>
          <input
            type="number"
            id="edit-stock"
            bind:value={selectedProduct.stock}
            class="mt-1 block w-full px-3 py-2 bg-white border border-gray-300 rounded-md"
            required
          />
        </div>
        <div>
          <label for="edit-image" class="block text-sm font-medium text-gray-700">Image URL</label>
          <input
            type="text"
            id="edit-image"
            bind:value={selectedProduct.image}
            class="mt-1 block w-full px-3 py-2 bg-white border border-gray-300 rounded-md"
            required
          />
        </div>
        <div class="flex justify-end space-x-3">
          <button
            type="button"
            class="px-4 py-2 text-gray-600 hover:text-gray-800"
            on:click={() => showEditModal = false}
          >
            Cancel
          </button>
          <button
            type="submit"
            class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700"
          >
            Save Changes
          </button>
        </div>
      </form>
    </div>
  </div>
{/if}

<!-- Delete Product Modal -->
{#if showDeleteModal && selectedProduct}
  <div class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center">
    <div class="bg-white p-6 rounded-lg w-96">
      <h2 class="text-xl font-bold mb-4">Confirm Delete</h2>
      <p class="mb-4">Are you sure you want to delete {selectedProduct.name}?</p>
      <div class="flex justify-end space-x-3">
        <button
          class="px-4 py-2 text-gray-600 hover:text-gray-800"
          on:click={() => showDeleteModal = false}
        >
          Cancel
        </button>
        <button
          class="px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700"
          on:click={handleDelete}
        >
          Delete
        </button>
      </div>
    </div>
  </div>
{/if} 
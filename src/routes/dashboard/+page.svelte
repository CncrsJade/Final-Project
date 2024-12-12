<script lang="ts">
  import Sidebar from '$lib/Sidebar.svelte';

  // Dummy data for dashboard
  const dashboardData = {
    todaySales: 0.00,
    totalProducts: 0,
    categories: 0,
    lowStockItems: 0
  };

  // Dummy data for employee list
  const employees = [
    { id: 1, name: 'John Doe', username: 'johndoe', role: 'Admin' },
    { id: 2, name: 'Jane Smith', username: 'janesmith', role: 'Manager' }
  ];

  // Modal state
  let showEditModal = false;
  let showDeleteModal = false;
  let selectedEmployee = null;

  function openEditModal(employee) {
    selectedEmployee = { ...employee };
    showEditModal = true;
  }

  function openDeleteModal(employee) {
    selectedEmployee = employee;
    showDeleteModal = true;
  }

  function handleEdit() {
    // Add edit logic here
    console.log('Editing employee:', selectedEmployee);
    showEditModal = false;
  }

  function handleDelete() {
    // Add delete logic here
    console.log('Deleting employee:', selectedEmployee);
    showDeleteModal = false;
  }
</script>

<div class="flex">
  <Sidebar />
  
  <main class="ml-56 p-6 w-full">
    <div class="flex justify-between items-center mb-6">
      <h1 class="text-2xl font-bold">Dashboard</h1>
      <button class="bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700">
        Refresh
      </button>
    </div>

    <div class="grid grid-cols-4 gap-6 mb-8">
      <div class="bg-blue-600 text-white p-6 rounded-lg">
        <h3 class="text-lg">Today's Sales</h3>
        <p class="text-2xl font-bold">₱{dashboardData.todaySales.toFixed(2)}</p>
      </div>
      
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

    <div class="bg-white rounded-lg shadow p-6">
      <h2 class="text-xl font-bold mb-4">Employee List</h2>
      <table class="w-full">
        <thead>
          <tr class="border-b">
            <th class="text-left py-2">ID</th>
            <th class="text-left py-2">Name</th>
            <th class="text-left py-2">Username</th>
            <th class="text-left py-2">Role</th>
            <th class="text-left py-2">Actions</th>
          </tr>
        </thead>
        <tbody>
          {#each employees as employee}
            <tr class="border-b">
              <td class="py-2">{employee.id}</td>
              <td class="py-2">{employee.name}</td>
              <td class="py-2">{employee.username}</td>
              <td class="py-2">{employee.role}</td>
              <td class="py-2">
                <button 
                  class="text-blue-600 hover:text-blue-800 mr-2"
                  on:click={() => openEditModal(employee)}
                >
                  Edit
                </button>
                <button 
                  class="text-red-600 hover:text-red-800"
                  on:click={() => openDeleteModal(employee)}
                >
                  Delete
                </button>
              </td>
            </tr>
          {/each}
        </tbody>
      </table>
    </div>
  </main>
</div>

<!-- Edit Modal -->
{#if showEditModal && selectedEmployee}
  <div class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center">
    <div class="bg-white p-6 rounded-lg w-96">
      <h2 class="text-xl font-bold mb-4">Edit Employee</h2>
      <form on:submit|preventDefault={handleEdit} class="space-y-4">
        <div>
          <label for="name" class="block text-sm font-medium text-gray-700">Name</label>
          <input
            type="text"
            id="name"
            bind:value={selectedEmployee.name}
            class="mt-1 block w-full px-3 py-2 bg-white border border-gray-300 rounded-md"
          />
        </div>
        <div>
          <label for="username" class="block text-sm font-medium text-gray-700">Username</label>
          <input
            type="text"
            id="username"
            bind:value={selectedEmployee.username}
            class="mt-1 block w-full px-3 py-2 bg-white border border-gray-300 rounded-md"
          />
        </div>
        <div>
          <label for="role" class="block text-sm font-medium text-gray-700">Role</label>
          <input
            type="text"
            id="role"
            bind:value={selectedEmployee.role}
            class="mt-1 block w-full px-3 py-2 bg-white border border-gray-300 rounded-md"
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

<!-- Delete Modal -->
{#if showDeleteModal && selectedEmployee}
  <div class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center">
    <div class="bg-white p-6 rounded-lg w-96">
      <h2 class="text-xl font-bold mb-4">Confirm Delete</h2>
      <p class="mb-4">Are you sure you want to delete {selectedEmployee.name}?</p>
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
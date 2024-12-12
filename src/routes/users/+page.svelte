<script lang="ts">
  import Sidebar from '$lib/Sidebar.svelte';

  interface User {
    id: number;
    name: string;
    username: string;
    email: string;
    role: string;
    status: 'Active' | 'Inactive';
  }

  // Dummy data for users
  let users: User[] = [
    {
      id: 1,
      name: 'John Doe',
      username: 'johndoe',
      email: 'john@example.com',
      role: 'Admin',
      status: 'Active'
    },
    {
      id: 2,
      name: 'Jane Smith',
      username: 'janesmith',
      email: 'jane@example.com',
      role: 'Manager',
      status: 'Active'
    }
  ];

  // Modal states
  let showAddModal = false;
  let showEditModal = false;
  let showDeleteModal = false;
  let selectedUser: User | null = null;

  // New user template
  let newUser = {
    name: '',
    username: '',
    email: '',
    role: 'Staff',
    status: 'Active' as const
  };

  function openAddModal() {
    newUser = {
      name: '',
      username: '',
      email: '',
      role: 'Staff',
      status: 'Active'
    };
    showAddModal = true;
  }

  function openEditModal(user: User) {
    selectedUser = { ...user };
    showEditModal = true;
  }

  function openDeleteModal(user: User) {
    selectedUser = user;
    showDeleteModal = true;
  }

  function handleAdd() {
    // Add user logic here
    console.log('Adding user:', newUser);
    showAddModal = false;
  }

  function handleEdit() {
    if (!selectedUser) return;
    // Add edit logic here
    console.log('Editing user:', selectedUser);
    showEditModal = false;
  }

  function handleDelete() {
    if (!selectedUser) return;
    // Add delete logic here
    console.log('Deleting user:', selectedUser);
    showDeleteModal = false;
  }
</script>

<div class="flex">
  <Sidebar />
  
  <main class="ml-56 p-6 w-full">
    <div class="flex justify-between items-center mb-6">
      <h1 class="text-2xl font-bold">Users</h1>
      <button
        class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 flex items-center gap-2"
        on:click={openAddModal}
      >
        <span>+</span> Add Employee
      </button>
    </div>

    <div class="mb-6">
      <input
        type="text"
        placeholder="Search users..."
        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
      />
    </div>

    <div class="bg-white rounded-lg shadow">
      <table class="w-full">
        <thead>
          <tr class="border-b">
            <th class="text-left p-4">ID</th>
            <th class="text-left p-4">Name</th>
            <th class="text-left p-4">Username</th>
            <th class="text-left p-4">Email</th>
            <th class="text-left p-4">Role</th>
            <th class="text-left p-4">Status</th>
            <th class="text-left p-4">Actions</th>
          </tr>
        </thead>
        <tbody>
          {#each users as user}
            <tr class="border-b">
              <td class="p-4">{user.id}</td>
              <td class="p-4">{user.name}</td>
              <td class="p-4">{user.username}</td>
              <td class="p-4">{user.email}</td>
              <td class="p-4">{user.role}</td>
              <td class="p-4">
                <span class="px-2 py-1 rounded-full text-sm {user.status === 'Active' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'}">
                  {user.status}
                </span>
              </td>
              <td class="p-4">
                <button
                  class="text-blue-600 hover:text-blue-800 mr-2"
                  on:click={() => openEditModal(user)}
                >
                  Edit
                </button>
                <button
                  class="text-red-600 hover:text-red-800"
                  on:click={() => openDeleteModal(user)}
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

<!-- Add User Modal -->
{#if showAddModal}
  <div class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center">
    <div class="bg-white p-6 rounded-lg w-96">
      <h2 class="text-xl font-bold mb-4">Add New User</h2>
      <form on:submit|preventDefault={handleAdd} class="space-y-4">
        <div>
          <label for="add-name" class="block text-sm font-medium text-gray-700">Name</label>
          <input
            type="text"
            id="add-name"
            bind:value={newUser.name}
            class="mt-1 block w-full px-3 py-2 bg-white border border-gray-300 rounded-md"
            required
          />
        </div>
        <div>
          <label for="add-username" class="block text-sm font-medium text-gray-700">Username</label>
          <input
            type="text"
            id="add-username"
            bind:value={newUser.username}
            class="mt-1 block w-full px-3 py-2 bg-white border border-gray-300 rounded-md"
            required
          />
        </div>
        <div>
          <label for="add-email" class="block text-sm font-medium text-gray-700">Email</label>
          <input
            type="email"
            id="add-email"
            bind:value={newUser.email}
            class="mt-1 block w-full px-3 py-2 bg-white border border-gray-300 rounded-md"
            required
          />
        </div>
        <div>
          <label for="add-role" class="block text-sm font-medium text-gray-700">Role</label>
          <select
            id="add-role"
            bind:value={newUser.role}
            class="mt-1 block w-full px-3 py-2 bg-white border border-gray-300 rounded-md"
          >
            <option value="Admin">Admin</option>
            <option value="Manager">Manager</option>
            <option value="Staff">Staff</option>
          </select>
        </div>
        <div>
          <label for="add-status" class="block text-sm font-medium text-gray-700">Status</label>
          <select
            id="add-status"
            bind:value={newUser.status}
            class="mt-1 block w-full px-3 py-2 bg-white border border-gray-300 rounded-md"
          >
            <option value="Active">Active</option>
            <option value="Inactive">Inactive</option>
          </select>
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
            Add User
          </button>
        </div>
      </form>
    </div>
  </div>
{/if}

<!-- Edit User Modal -->
{#if showEditModal && selectedUser}
  <div class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center">
    <div class="bg-white p-6 rounded-lg w-96">
      <h2 class="text-xl font-bold mb-4">Edit User</h2>
      <form on:submit|preventDefault={handleEdit} class="space-y-4">
        <div>
          <label for="name" class="block text-sm font-medium text-gray-700">Name</label>
          <input
            type="text"
            id="name"
            bind:value={selectedUser.name}
            class="mt-1 block w-full px-3 py-2 bg-white border border-gray-300 rounded-md"
            required
          />
        </div>
        <div>
          <label for="username" class="block text-sm font-medium text-gray-700">Username</label>
          <input
            type="text"
            id="username"
            bind:value={selectedUser.username}
            class="mt-1 block w-full px-3 py-2 bg-white border border-gray-300 rounded-md"
            required
          />
        </div>
        <div>
          <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
          <input
            type="email"
            id="email"
            bind:value={selectedUser.email}
            class="mt-1 block w-full px-3 py-2 bg-white border border-gray-300 rounded-md"
            required
          />
        </div>
        <div>
          <label for="role" class="block text-sm font-medium text-gray-700">Role</label>
          <select
            id="role"
            bind:value={selectedUser.role}
            class="mt-1 block w-full px-3 py-2 bg-white border border-gray-300 rounded-md"
          >
            <option value="Admin">Admin</option>
            <option value="Manager">Manager</option>
            <option value="Staff">Staff</option>
          </select>
        </div>
        <div>
          <label for="status" class="block text-sm font-medium text-gray-700">Status</label>
          <select
            id="status"
            bind:value={selectedUser.status}
            class="mt-1 block w-full px-3 py-2 bg-white border border-gray-300 rounded-md"
          >
            <option value="Active">Active</option>
            <option value="Inactive">Inactive</option>
          </select>
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

<!-- Delete User Modal -->
{#if showDeleteModal && selectedUser}
  <div class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center">
    <div class="bg-white p-6 rounded-lg w-96">
      <h2 class="text-xl font-bold mb-4">Confirm Delete</h2>
      <p class="mb-4">Are you sure you want to delete user {selectedUser.name}?</p>
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
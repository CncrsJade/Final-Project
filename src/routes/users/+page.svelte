<script lang="ts">
  import { onMount } from 'svelte';
  import { getUsers, createUser, deleteUser, updateUserRole } from '$lib/api';
  import Sidebar from '$lib/Sidebar.svelte';
  import { auth } from '$lib';

  let users = [];
  let loading = false;
  let error = '';
  let showAddModal = false;
  let showEditModal = false;
  let selectedUser = null;

  let newUser = {
    username: '',
    password: '',
    role: 'user'
  };

  onMount(async () => {
    await loadUsers();
  });

  async function loadUsers() {
    loading = true;
    error = '';
    
    try {
      users = await getUsers();
    } catch (e) {
      error = 'Failed to load users';
      console.error('Error loading users:', e);
    } finally {
      loading = false;
    }
  }

  async function handleAdd() {
    loading = true;
    error = '';
    
    try {
      const result = await createUser(newUser);
      
      if (result.success) {
        showAddModal = false;
        newUser = { username: '', password: '', role: 'user' };
        await loadUsers();
      } else {
        error = result.message || 'Failed to create user';
      }
    } catch (e) {
      error = e.message || 'An error occurred';
    } finally {
      loading = false;
    }
  }

  async function handleDelete(userId: number) {
    if (!confirm('Are you sure you want to delete this user?')) return;
    
    loading = true;
    error = '';
    
    try {
      const result = await deleteUser(userId);
      
      if (result.success) {
        await loadUsers();
      } else {
        error = result.message || 'Failed to delete user';
      }
    } catch (e) {
      error = e.message || 'An error occurred';
    } finally {
      loading = false;
    }
  }

  function openEditModal(user) {
    selectedUser = { ...user };
    showEditModal = true;
  }

  async function handleEdit() {
    loading = true;
    error = '';
    
    try {
      const result = await updateUserRole(selectedUser.id, selectedUser.role);
      
      if (result.success) {
        showEditModal = false;
        await loadUsers();
      } else {
        error = result.message || 'Failed to update user role';
      }
    } catch (e) {
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
      <h1 class="text-2xl font-bold">Users Management</h1>
      <button
        class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700"
        on:click={() => showAddModal = true}
      >
        Add User
      </button>
    </div>

    {#if error}
      <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
        {error}
      </div>
    {/if}

    {#if loading && !users.length}
      <div class="flex justify-center items-center h-64">
        <div class="text-gray-500">Loading users...</div>
      </div>
    {:else}
      <div class="bg-white rounded-lg shadow overflow-hidden">
        <table class="min-w-full">
          <thead class="bg-gray-50">
            <tr>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                Username
              </th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                Role
              </th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                Created At
              </th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                Actions
              </th>
            </tr>
          </thead>
          <tbody class="bg-white divide-y divide-gray-200">
            {#each users as user}
              <tr>
                <td class="px-6 py-4 whitespace-nowrap">
                  {user.username}
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                  <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                    {user.role === 'admin' ? 'bg-purple-100 text-purple-800' : 'bg-green-100 text-green-800'}">
                    {user.role}
                  </span>
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                  {new Date(user.created_at).toLocaleDateString()}
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium space-x-3">
                  {#if user.id !== $auth?.id}
                    <button 
                      class="text-blue-600 hover:text-blue-900"
                      on:click={() => openEditModal(user)}
                    >
                      Edit Role
                    </button>
                    <button 
                      class="text-red-600 hover:text-red-900"
                      on:click={() => handleDelete(user.id)}
                    >
                      Delete
                    </button>
                  {/if}
                </td>
              </tr>
            {/each}
          </tbody>
        </table>
      </div>
    {/if}
  </main>
</div>

<!-- Add User Modal -->
{#if showAddModal}
  <div class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center">
    <div class="bg-white p-6 rounded-lg w-96">
      <h2 class="text-xl font-bold mb-4">Add User</h2>
      
      {#if error}
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
          {error}
        </div>
      {/if}
      
      <form on:submit|preventDefault={handleAdd} class="space-y-4">
        <div>
          <label for="username" class="block text-sm font-medium text-gray-700">Username</label>
          <input
            type="text"
            id="username"
            bind:value={newUser.username}
            disabled={loading}
            class="mt-1 block w-full px-3 py-2 bg-white border border-gray-300 rounded-md"
            required
          />
        </div>
        
        <div>
          <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
          <input
            type="password"
            id="password"
            bind:value={newUser.password}
            disabled={loading}
            class="mt-1 block w-full px-3 py-2 bg-white border border-gray-300 rounded-md"
            required
          />
        </div>
        
        <div>
          <label for="role" class="block text-sm font-medium text-gray-700">Role</label>
          <select
            id="role"
            bind:value={newUser.role}
            disabled={loading}
            class="mt-1 block w-full px-3 py-2 bg-white border border-gray-300 rounded-md"
          >
            <option value="user">User</option>
            <option value="admin">Admin</option>
          </select>
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
            {loading ? 'Adding...' : 'Add User'}
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
      <h2 class="text-xl font-bold mb-4">Edit User Role</h2>
      
      {#if error}
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
          {error}
        </div>
      {/if}
      
      <form on:submit|preventDefault={handleEdit} class="space-y-4">
        <div>
          <p class="text-sm text-gray-600 mb-2">Username: {selectedUser.username}</p>
        </div>
        
        <div>
          <label for="edit-role" class="block text-sm font-medium text-gray-700">Role</label>
          <select
            id="edit-role"
            bind:value={selectedUser.role}
            disabled={loading}
            class="mt-1 block w-full px-3 py-2 bg-white border border-gray-300 rounded-md"
          >
            <option value="user">User</option>
            <option value="admin">Admin</option>
          </select>
        </div>
        
        <div class="flex justify-end space-x-3">
          <button
            type="button"
            class="px-4 py-2 text-gray-600 hover:text-gray-800"
            disabled={loading}
            on:click={() => showEditModal = false}
          >
            Cancel
          </button>
          <button
            type="submit"
            class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 disabled:opacity-50"
            disabled={loading}
          >
            {loading ? 'Saving...' : 'Save Changes'}
          </button>
        </div>
      </form>
    </div>
  </div>
{/if} 
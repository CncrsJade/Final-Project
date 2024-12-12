<script lang="ts">
  import { auth } from '$lib';
  import { updatePassword } from '$lib/api';
  import Sidebar from '$lib/Sidebar.svelte';

  let currentPassword = '';
  let newPassword = '';
  let confirmPassword = '';
  let loading = false;
  let error = '';
  let success = '';

  async function handlePasswordChange() {
    loading = true;
    error = '';
    success = '';

    try {
      if (newPassword !== confirmPassword) {
        throw new Error("New passwords don't match");
      }

      const result = await updatePassword($auth.id, currentPassword, newPassword);

      if (result.success) {
        success = 'Password updated successfully';
        currentPassword = '';
        newPassword = '';
        confirmPassword = '';
      } else {
        error = result.message || 'Failed to update password';
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
    <div class="max-w-xl mx-auto">
      <h1 class="text-2xl font-bold mb-6">Account Settings</h1>
      
      <div class="bg-white rounded-lg shadow p-6">
        <h2 class="text-xl font-semibold mb-4">Change Password</h2>
        
        {#if error}
          <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
            {error}
          </div>
        {/if}

        {#if success}
          <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
            {success}
          </div>
        {/if}
        
        <form on:submit|preventDefault={handlePasswordChange} class="space-y-4">
          <div>
            <label for="currentPassword" class="block text-sm font-medium text-gray-700">
              Current Password
            </label>
            <input
              type="password"
              id="currentPassword"
              bind:value={currentPassword}
              disabled={loading}
              class="mt-1 block w-full px-3 py-2 bg-white border border-gray-300 rounded-md"
              required
            />
          </div>
          
          <div>
            <label for="newPassword" class="block text-sm font-medium text-gray-700">
              New Password
            </label>
            <input
              type="password"
              id="newPassword"
              bind:value={newPassword}
              disabled={loading}
              class="mt-1 block w-full px-3 py-2 bg-white border border-gray-300 rounded-md"
              required
            />
          </div>
          
          <div>
            <label for="confirmPassword" class="block text-sm font-medium text-gray-700">
              Confirm New Password
            </label>
            <input
              type="password"
              id="confirmPassword"
              bind:value={confirmPassword}
              disabled={loading}
              class="mt-1 block w-full px-3 py-2 bg-white border border-gray-300 rounded-md"
              required
            />
          </div>
          
          <button
            type="submit"
            class="w-full bg-blue-600 text-white py-2 px-4 rounded-md hover:bg-blue-700 disabled:opacity-50"
            disabled={loading}
          >
            {loading ? 'Updating...' : 'Update Password'}
          </button>
        </form>
      </div>
    </div>
  </main>
</div> 
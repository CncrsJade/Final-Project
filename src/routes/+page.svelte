<script lang="ts">
  import { goto } from '$app/navigation';
  let username = '';
  let password = '';

  async function handleLogin() {
    // For now, just redirect to dashboard without authentication
    await goto('/dashboard');
  }
</script>

<div class="min-h-screen flex items-center justify-center bg-gray-100">
  <div class="bg-white p-8 rounded-lg shadow-md w-96">
    <h1 class="text-2xl font-bold mb-6 text-center">Inventory System</h1>
    
    <form on:submit|preventDefault={handleLogin} class="space-y-4">
      <div>
        <label for="username" class="block text-sm font-medium text-gray-700">Username</label>
        <input
          type="text"
          id="username"
          bind:value={username}
          placeholder="Enter your username"
          class="mt-1 block w-full px-3 py-2 bg-white border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-blue-500 focus:border-blue-500"
        />
      </div>
      
      <div>
        <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
        <input
          type="password"
          id="password"
          bind:value={password}
          placeholder="Enter your password"
          class="mt-1 block w-full px-3 py-2 bg-white border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-blue-500 focus:border-blue-500"
        />
      </div>
      
      <button
        type="submit"
        class="w-full bg-blue-600 text-white py-2 px-4 rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2"
      >
        Login
      </button>
    </form>
    
    <div class="mt-4 text-center">
      <p class="text-sm text-gray-600">
        Don't have an account? 
        <a href="/register" class="text-blue-600 hover:text-blue-800 font-medium">
          Register here
        </a>
      </p>
    </div>
  </div>
</div>

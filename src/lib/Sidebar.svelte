<script lang="ts">
  import { page } from '$app/stores';
  import { auth } from '$lib';
</script>

<aside class="fixed left-0 top-0 h-screen w-56 bg-gray-800 text-white p-4">
  <nav class="space-y-2">
    <a 
      href="/dashboard" 
      class="block px-4 py-2 rounded-lg hover:bg-gray-700 {$page.url.pathname === '/dashboard' ? 'bg-gray-700' : ''}"
    >
      Dashboard
    </a>
    
    <a 
      href="/products" 
      class="block px-4 py-2 rounded-lg hover:bg-gray-700 {$page.url.pathname === '/products' ? 'bg-gray-700' : ''}"
    >
      Products
    </a>
    
    <a 
      href="/categories" 
      class="block px-4 py-2 rounded-lg hover:bg-gray-700 {$page.url.pathname === '/categories' ? 'bg-gray-700' : ''}"
    >
      Categories
    </a>
    
    {#if $auth?.role === 'admin'}
      <a 
        href="/users" 
        class="block px-4 py-2 rounded-lg hover:bg-gray-700 {$page.url.pathname === '/users' ? 'bg-gray-700' : ''}"
      >
        Users
      </a>
    {/if}

    <a 
      href="/account" 
      class="block px-4 py-2 rounded-lg hover:bg-gray-700 {$page.url.pathname === '/account' ? 'bg-gray-700' : ''}"
    >
      Account
    </a>
  </nav>
  
  <div class="absolute bottom-4 left-4 right-4">
    <button
      class="w-full px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700"
      on:click={() => auth.logout()}
    >
      Logout
    </button>
  </div>
</aside>

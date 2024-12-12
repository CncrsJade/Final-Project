<script lang="ts">
  import { page } from '$app/stores';
  import { goto } from '$app/navigation';
  
  const menuItems = [
    { icon: '📊', label: 'Dashboard', href: '/dashboard' },
    { icon: '📦', label: 'Products', href: '/products' },
    { icon: '👥', label: 'Users', href: '/users' },
  ];

  async function handleLogout() {
    // Add any logout logic here (clearing session, etc.)
    await goto('/');
  }
</script>

<aside class="fixed left-0 top-0 h-screen w-56 bg-gray-900 text-white p-4">
  <div class="flex flex-col h-full">
    <div class="mb-8">
      <h1 class="text-xl font-bold">Inventory System</h1>
    </div>
    
    <nav class="flex-1">
      <ul class="space-y-2">
        {#each menuItems as item}
          <li>
            <a
              href={item.href}
              class="flex items-center gap-3 px-4 py-2 rounded-lg hover:bg-gray-800 transition-colors {$page.url.pathname === item.href ? 'bg-gray-800' : ''}"
            >
              <span>{item.icon}</span>
              <span>{item.label}</span>
            </a>
          </li>
        {/each}
      </ul>
    </nav>
    
    <div class="mt-auto">
      <button
        on:click={handleLogout}
        class="w-full flex items-center gap-3 px-4 py-2 text-red-400 hover:bg-gray-800 rounded-lg transition-colors"
      >
        <span>🚪</span>
        <span>Logout</span>
      </button>
    </div>
  </div>
</aside>

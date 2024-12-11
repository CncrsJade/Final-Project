<script lang="ts">
    import { page } from '$app/stores';
    import { signOut } from '$lib/stores/auth';

    const navigation = [
        { name: 'Dashboard', href: '/' },
        { name: 'Products', href: '/products' },
        { name: 'Orders', href: '/orders' }
    ];
</script>

<nav class="fixed left-0 top-0 h-full w-[250px] bg-gray-800 text-white p-4">
    <div class="mb-8">
        <h1 class="text-2xl font-bold">POS Namin</h1>
    </div>

    <ul class="space-y-2">
        {#each navigation as item}
            <li>
                <a
                    href={item.href}
                    class="block px-4 py-2 rounded-md {$page.url.pathname === item.href ? 'bg-gray-700' : 'hover:bg-gray-700'}"
                >
                    {item.name}
                </a>
            </li>
        {/each}
    </ul>

    <div class="absolute bottom-4 left-4 right-4">
        <button
            class="w-full px-4 py-2 text-white bg-red-500 hover:bg-red-600 rounded-md"
            on:click={signOut}
        >
            Sign Out
        </button>
    </div>
</nav> 
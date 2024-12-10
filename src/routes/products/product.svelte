<script lang="ts">
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

    let searchQuery = '';
    let viewMode: 'grid' | 'list' = 'grid';
</script>

<div class="container mx-auto p-4">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold">Products</h1>
        <button class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded">
            + Add Product
        </button>
    </div>

    <div class="flex justify-between items-center mb-6">
        <div class="relative flex-1 max-w-xl">
            <input
                type="text"
                bind:value={searchQuery}
                placeholder="Search products..."
                class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:border-blue-500"
            />
            <span class="absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400">
                
            </span>
        </div>
        
        <div class="flex gap-2 ml-4">
            <button
                class="px-3 py-2 rounded {viewMode === 'grid' ? 'bg-gray-600 text-white' : 'bg-gray-200'}"
                on:click={() => viewMode = 'grid'}
            >
                Grid
            </button>
            <button
                class="px-3 py-2 rounded {viewMode === 'list' ? 'bg-gray-600 text-white' : 'bg-gray-200'}"
                on:click={() => viewMode = 'list'}
            >
                List
            </button>
        </div>
    </div>

    <div class={viewMode === 'grid' ? 'grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6' : 'space-y-4'}>
        {#each products as product (product.id)}
            <div class="bg-white rounded-lg shadow-md overflow-hidden">
                <img
                    src={product.image}
                    alt={product.name}
                    class="w-full h-48 object-cover"
                />
                <div class="p-4">
                    <h2 class="text-xl font-semibold">{product.name}</h2>
                    <div class="mt-2">
                        <p>Price: ₱{product.price.toFixed(2)}</p>
                        <p>Stock: {product.stock} pcs</p>
                    </div>
                    <div class="mt-4 flex gap-2">
                        <button class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded flex-1">
                            Edit
                        </button>
                        <button class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded flex-1">
                            Delete
                        </button>
                    </div>
                </div>
            </div>
        {/each}
    </div>
</div>

<style>
    /* Add any additional custom styles here */
    :global(body) {
        background-color: #f5f5f5;
    }
</style> 
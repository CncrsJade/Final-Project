<script lang="ts">
  export let isOpen = false;
  export let mode: 'add' | 'edit' = 'add';
  export let productData = {
    name: '',
    price: 0,
    stock: 0,
    image: ''
  };
  
  function closeModal() {
    isOpen = false;
  }

  $: modalTitle = mode === 'add' ? 'Add New Product' : 'Edit Product';
  $: submitButtonText = mode === 'add' ? 'Add Product' : 'Save Changes';
</script>

<style>
  .modal-backdrop {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0, 0, 0, 0.5);
    display: flex;
    justify-content: center;
    align-items: center;
    z-index: 1000;
  }

  .modal-content {
    background-color: white;
    padding: 2rem;
    border-radius: 0.5rem;
    width: 100%;
    max-width: 500px;
    position: relative;
  }

  .close-button {
    position: absolute;
    top: 1rem;
    right: 1rem;
    background: none;
    border: none;
    font-size: 1.5rem;
    cursor: pointer;
  }

  .form-group {
    margin-bottom: 1rem;
  }

  .form-group label {
    display: block;
    margin-bottom: 0.5rem;
    font-weight: 500;
  }

  .form-group input {
    width: 100%;
    padding: 0.5rem;
    border: 1px solid #ddd;
    border-radius: 0.25rem;
  }

  .submit-button {
    background-color: #4CAF50;
    color: white;
    padding: 0.5rem 1rem;
    border: none;
    border-radius: 0.25rem;
    cursor: pointer;
    width: 100%;
  }

  .submit-button:hover {
    background-color: #45a049;
  }

  .image-preview {
    width: 100%;
    max-height: 200px;
    object-fit: contain;
    margin-bottom: 1rem;
  }
</style>

{#if isOpen}
  <div class="modal-backdrop" on:click|self={closeModal}>
    <div class="modal-content">
      <button class="close-button" on:click={closeModal}>&times;</button>
      <h2 class="text-xl font-bold mb-4">{modalTitle}</h2>
      
      <form>
        <div class="form-group">
          <label for="name">Product Name</label>
          <input 
            type="text" 
            id="name" 
            placeholder="Enter product name"
            bind:value={productData.name}
          >
        </div>

        <div class="form-group">
          <label for="price">Price (₱)</label>
          <input 
            type="number" 
            id="price" 
            placeholder="Enter price"
            bind:value={productData.price}
            min="0"
            step="0.01"
          >
        </div>

        <div class="form-group">
          <label for="stock">Stock</label>
          <input 
            type="number" 
            id="stock" 
            placeholder="Enter stock quantity"
            bind:value={productData.stock}
            min="0"
          >
        </div>

        <div class="form-group">
          <label for="image">Image URL</label>
          <input 
            type="text" 
            id="image" 
            placeholder="Enter image URL"
            bind:value={productData.image}
          >
        </div>

        {#if productData.image}
          <img 
            src={productData.image} 
            alt="Product preview" 
            class="image-preview"
          />
        {/if}

        <button type="submit" class="submit-button">{submitButtonText}</button>
      </form>
    </div>
  </div>
{/if} 
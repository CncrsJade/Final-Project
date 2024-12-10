<script lang="ts">
  export let isOpen = false;
  export let mode: 'add' | 'edit' = 'add';
  export let userData = {
    name: '',
    email: '',
    role: 'user'
  };
  
  function closeModal() {
    isOpen = false;
  }

  $: modalTitle = mode === 'add' ? 'Add New User' : 'Edit User';
  $: submitButtonText = mode === 'add' ? 'Add User' : 'Save Changes';
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

  .form-group input, .form-group select {
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
  }

  .submit-button:hover {
    background-color: #45a049;
  }
</style>

{#if isOpen}
  <div class="modal-backdrop" on:click|self={closeModal}>
    <div class="modal-content">
      <button class="close-button" on:click={closeModal}>&times;</button>
      <h2 class="text-xl font-bold mb-4">{modalTitle}</h2>
      
      <form>
        <div class="form-group">
          <label for="name">Name</label>
          <input 
            type="text" 
            id="name" 
            placeholder="Enter name"
            bind:value={userData.name}
          >
        </div>

        <div class="form-group">
          <label for="email">Email</label>
          <input 
            type="email" 
            id="email" 
            placeholder="Enter email"
            bind:value={userData.email}
          >
        </div>

        <div class="form-group">
          <label for="role">Role</label>
          <select id="role" bind:value={userData.role}>
            <option value="user">User</option>
            <option value="admin">Admin</option>
          </select>
        </div>

        <button type="submit" class="submit-button">{submitButtonText}</button>
      </form>
    </div>
  </div>
{/if} 
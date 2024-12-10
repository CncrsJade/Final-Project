<script lang="ts">
  import UserModal from './UserModal.svelte';
  
  let isModalOpen = false;
  let modalMode: 'add' | 'edit' = 'add';
  let currentUserData = {
    name: '',
    email: '',
    role: 'user'
  };

  function openAddModal() {
    modalMode = 'add';
    currentUserData = {
      name: '',
      email: '',
      role: 'user'
    };
    isModalOpen = true;
  }

  function openEditModal(user: any) {
    modalMode = 'edit';
    currentUserData = {
      name: user.name,
      email: user.email,
      role: user.role.toLowerCase()
    };
    isModalOpen = true;
  }
</script>

<div class="p-4">
  <div class="flex justify-between items-center mb-6">
    <h1 class="text-2xl font-bold">Users</h1>
    <button 
      class="bg-blue-500 text-white px-4 py-2 rounded-md hover:bg-blue-600"
      on:click={openAddModal}
    >
      + Add User
    </button>
  </div>

  <div class="overflow-x-auto">
    <table class="min-w-full bg-white">
      <thead>
        <tr>
          <th class="px-6 py-3 border-b-2 border-gray-200 text-left">Name</th>
          <th class="px-6 py-3 border-b-2 border-gray-200 text-left">Email</th>
          <th class="px-6 py-3 border-b-2 border-gray-200 text-left">Role</th>
          <th class="px-6 py-3 border-b-2 border-gray-200 text-left">Actions</th>
        </tr>
      </thead>
      <tbody>
        <tr>
          <td class="px-6 py-4 border-b border-gray-200">John Doe</td>
          <td class="px-6 py-4 border-b border-gray-200">john@example.com</td>
          <td class="px-6 py-4 border-b border-gray-200">Admin</td>
          <td class="px-6 py-4 border-b border-gray-200">
            <button 
              class="text-blue-500 hover:text-blue-700 mr-2"
              on:click={() => openEditModal({
                name: 'John Doe',
                email: 'john@example.com',
                role: 'Admin'
              })}
            >
              Edit
            </button>
            <button class="text-red-500 hover:text-red-700">Delete</button>
          </td>
        </tr>
        <tr>
          <td class="px-6 py-4 border-b border-gray-200">Jane Smith</td>
          <td class="px-6 py-4 border-b border-gray-200">jane@example.com</td>
          <td class="px-6 py-4 border-b border-gray-200">User</td>
          <td class="px-6 py-4 border-b border-gray-200">
            <button 
              class="text-blue-500 hover:text-blue-700 mr-2"
              on:click={() => openEditModal({
                name: 'Jane Smith',
                email: 'jane@example.com',
                role: 'User'
              })}
            >
              Edit
            </button>
            <button class="text-red-500 hover:text-red-700">Delete</button>
          </td>
        </tr>
      </tbody>
    </table>
  </div>
</div>

<UserModal 
  bind:isOpen={isModalOpen}
  mode={modalMode}
  bind:userData={currentUserData}
/> 
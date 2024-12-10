<script lang="ts">
  let users = [
    { id: 1, name: 'John Doe', email: 'john@example.com', role: 'Admin' },
    { id: 2, name: 'Jane Smith', email: 'jane@example.com', role: 'User' },
  ];
</script>

<div class="container mx-auto p-4">
  <div class="flex justify-between items-center mb-6">
    <h1 class="text-2xl font-bold">Users</h1>
    <button class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded">
      + Add User
    </button>
  </div>

  <div class="bg-white rounded-lg shadow">
    <table class="min-w-full">
      <thead>
        <tr class="bg-gray-100">
          <th class="px-6 py-3 text-left">Name</th>
          <th class="px-6 py-3 text-left">Email</th>
          <th class="px-6 py-3 text-left">Role</th>
          <th class="px-6 py-3 text-left">Actions</th>
        </tr>
      </thead>
      <tbody>
        {#each users as user}
          <tr class="border-t">
            <td class="px-6 py-4">{user.name}</td>
            <td class="px-6 py-4">{user.email}</td>
            <td class="px-6 py-4">{user.role}</td>
            <td class="px-6 py-4">
              <button class="text-blue-500 hover:text-blue-700 mr-2">Edit</button>
              <button class="text-red-500 hover:text-red-700">Delete</button>
            </td>
          </tr>
        {/each}
      </tbody>
    </table>
  </div>
</div>

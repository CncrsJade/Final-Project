<script lang="ts">
  let name = '';
  let username = '';
  let password = '';
  let confirmPassword = '';

  function handleRegister() {
    // Add registration logic here
    if (password !== confirmPassword) {
      alert("Passwords don't match!");
      return;
    }
    console.log('Registration attempted:', { name, username, password });
  }
</script>

<div class="min-h-screen flex items-center justify-center bg-gray-100">
  <div class="bg-white p-8 rounded-lg shadow-md w-96">
    <h1 class="text-2xl font-bold mb-6 text-center">Create Account</h1>
    
    <form on:submit|preventDefault={handleRegister} class="space-y-4">
      <div>
        <label for="name" class="block text-sm font-medium text-gray-700">Full Name</label>
        <input
          type="text"
          id="name"
          bind:value={name}
          placeholder="Enter your full name"
          class="mt-1 block w-full px-3 py-2 bg-white border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-blue-500 focus:border-blue-500"
          required
        />
      </div>

      <div>
        <label for="username" class="block text-sm font-medium text-gray-700">Username</label>
        <input
          type="text"
          id="username"
          bind:value={username}
          placeholder="Choose a username"
          class="mt-1 block w-full px-3 py-2 bg-white border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-blue-500 focus:border-blue-500"
          required
        />
      </div>
      
      <div>
        <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
        <input
          type="password"
          id="password"
          bind:value={password}
          placeholder="Create a password"
          class="mt-1 block w-full px-3 py-2 bg-white border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-blue-500 focus:border-blue-500"
          required
        />
      </div>

      <div>
        <label for="confirmPassword" class="block text-sm font-medium text-gray-700">Confirm Password</label>
        <input
          type="password"
          id="confirmPassword"
          bind:value={confirmPassword}
          placeholder="Confirm your password"
          class="mt-1 block w-full px-3 py-2 bg-white border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-blue-500 focus:border-blue-500"
          required
        />
      </div>
      
      <button
        type="submit"
        class="w-full bg-blue-600 text-white py-2 px-4 rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2"
      >
        Register
      </button>
    </form>
    
    <div class="mt-4 text-center">
      <p class="text-sm text-gray-600">
        Already have an account? 
        <a href="/" class="text-blue-600 hover:text-blue-800 font-medium">
          Login here
        </a>
      </p>
    </div>
  </div>
</div> 
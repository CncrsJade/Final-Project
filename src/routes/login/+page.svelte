<script lang="ts">
  import { currentUser, isAuthenticated } from '$lib/stores/auth';
  import { goto } from '$app/navigation';

  let username = '';
  let password = '';
  let error = '';

  async function handleLogin() {
    try {
      const response = await fetch('/api/auth', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
        },
        body: JSON.stringify({ username, password }),
      });

      const data = await response.json();

      if (response.ok) {
        $currentUser = data.user;
        $isAuthenticated = true;
        goto('/dashboard');
      } else {
        error = data.error;
      }
    } catch (err) {
      error = 'Failed to login. Please try again.';
    }
  }
</script>

<div class="login-container">
  <form on:submit|preventDefault={handleLogin}>
    <h1>Login</h1>
    
    {#if error}
      <div class="error">{error}</div>
    {/if}

    <div class="input-group">
      <label for="username">Username</label>
      <input 
        type="text" 
        id="username" 
        bind:value={username} 
        required
      />
    </div>

    <div class="input-group">
      <label for="password">Password</label>
      <input 
        type="password" 
        id="password" 
        bind:value={password} 
        required
      />
    </div>

    <button type="submit">Login</button>
    <p class="text-center mt-4">
      Don't have an account? <a href="/signup">Sign Up</a>
    </p>
  </form>
</div>

<style>
  .login-container {
    max-width: 400px;
    margin: 100px auto;
    padding: 30px;
    border: 1px solid hsl(197, 82%, 26%);
    border-radius: 10px;
    box-shadow: 0 2px 4px rgba(79, 69, 69, 0.1);
  }

  h1 {
    text-align: center;
    font-family: roboto;
    font-style: bold;
    font-size: 30px;
  }

  .input-group {
    margin-bottom: 15px;
    color: hsl(197, 82%, 26%);
  }

  .error {
    color: red;
    margin-bottom: 15px;
  }
</style> 
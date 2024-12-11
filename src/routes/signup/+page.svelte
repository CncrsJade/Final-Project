<script lang="ts">
    import { goto } from '$app/navigation';
    import { currentUser, isAuthenticated } from '$lib/stores/auth';

    let username = '';
    let password = '';
    let confirmPassword = '';
    let error = '';

    async function handleSignup() {
        if (password !== confirmPassword) {
            error = 'Passwords do not match';
            return;
        }

        try {
            const response = await fetch('/api/auth/signup', {
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
            error = 'Failed to create account. Please try again.';
        }
    }
</script>

<div class="login-container">
    <form on:submit|preventDefault={handleSignup}>
        <h1>Sign Up</h1>
        
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

        <div class="input-group">
            <label for="confirmPassword">Confirm Password</label>
            <input 
                type="password" 
                id="confirmPassword" 
                bind:value={confirmPassword} 
                required
            />
        </div>

        <button type="submit">Sign Up</button>
        <p class="text-center mt-4">
            Already have an account? <a href="/login" class="text-blue-500">Login</a>
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
        margin-bottom: 20px;
    }

    .input-group {
        margin-bottom: 15px;
        color: hsl(197, 82%, 26%);
    }

    .error {
        color: red;
        margin-bottom: 15px;
        text-align: center;
    }

    button {
        width: 100%;
        padding: 10px;
        background-color: hsl(197, 82%, 26%);
        color: white;
        border: none;
        border-radius: 5px;
        cursor: pointer;
    }

    button:hover {
        background-color: hsl(197, 82%, 20%);
    }

    input {
        width: 100%;
        padding: 8px;
        border: 1px solid #ccc;
        border-radius: 4px;
    }

    .text-center {
        text-align: center;
    }

    a {
        color: hsl(197, 82%, 26%);
        text-decoration: none;
    }

    a:hover {
        text-decoration: underline;
    }
</style>
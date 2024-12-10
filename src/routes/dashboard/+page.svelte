<script>
  import { onMount } from 'svelte';
  import { goto } from '$app/navigation';
  import { isAuthenticated } from '$lib/stores/auth';
  import Dashboard from "./dashboard.svelte";

  onMount(() => {
    if (!$isAuthenticated) {
      goto('/login');
    }
  });
</script>

<Dashboard /> 
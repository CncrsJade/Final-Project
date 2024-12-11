<script lang="ts">
	import '../app.css';
	import Navbar from './components/navbar.svelte';
	import { isAuthenticated } from '$lib/stores/auth';
</script>

<div class="flex min-h-screen">
	{#if $isAuthenticated}
		<Navbar />
		<div class="flex-1 ml-[250px]">
			<slot />
		</div>
	{:else}
		<div class="flex-1">
			<slot />
		</div>
	{/if}
</div>

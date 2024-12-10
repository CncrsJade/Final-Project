<script lang="ts">
	import '../app.css';
	import Navbar from './components/navbar.svelte';
	import { isAuthenticated } from '$lib/stores/auth';
	let { children } = $props();
</script>

<div class="flex min-h-screen">
	{#if $isAuthenticated}
		<Navbar />
		<div class="flex-1 ml-[250px]">
			{@render children()}
		</div>
	{:else}
		<div class="flex-1">
			{@render children()}
		</div>
	{/if}
</div>

import { sveltekit } from '@sveltejs/kit/vite';
import { defineConfig } from 'vite';

export default defineConfig(({ mode }) => ({
	plugins: [sveltekit()],
	server: {
		proxy: mode === 'development' ? {
			'/api': {
				target: 'http://localhost/Final-Project/api/',
				changeOrigin: true,
				rewrite: (path) => path.replace(/^\/api/, ''),
				secure: true
			}
		} : undefined
	}
}));

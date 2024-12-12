import { sveltekit } from '@sveltejs/kit/vite';
import { defineConfig } from 'vite';

export default defineConfig({
	plugins: [sveltekit()],
	server: {
		proxy: {
			'/api': {
				target: 'http://localhost/Final-Project/api',
				changeOrigin: true,
				secure: false,
				configure: (proxy, options) => {
					proxy.on('error', (err, req, res) => {
						console.log('proxy error', err);
					});
					proxy.on('proxyReq', (proxyReq, req, res) => {
						console.log('Sending Request:', {
							method: req.method,
							url: req.url,
							targetUrl: proxyReq.path,
							headers: proxyReq.getHeaders()
						});
					});
					proxy.on('proxyRes', (proxyRes, req, res) => {
						console.log('Received Response:', {
							status: proxyRes.statusCode,
							url: req.url,
							method: req.method
						});
					});
				}
			}
		}
	}
});

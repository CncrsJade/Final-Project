import { auth } from '$lib';
import { browser } from '$app/environment';
import { goto } from '$app/navigation';
import type { LayoutLoad } from './$types';

export const load: LayoutLoad = async ({ url }) => {
  if (browser) {
    auth.initialize();
    
    // Check if user is authenticated for protected routes
    const protectedRoutes = ['/dashboard', '/products', '/categories', '/users'];
    const isProtectedRoute = protectedRoutes.some(route => url.pathname.startsWith(route));
    
    const user = localStorage.getItem('user');
    if (isProtectedRoute && !user) {
      goto('/');
      return;
    }
  }
};

export const prerender = true;
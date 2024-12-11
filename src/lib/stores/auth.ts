import { writable } from 'svelte/store';

interface User {
    id: number;
    username: string;
    role: string;
}

export const currentUser = writable<User | null>(null);
export const isAuthenticated = writable<boolean>(false);

export function signOut() {
    currentUser.set(null);
    isAuthenticated.set(false);
} 
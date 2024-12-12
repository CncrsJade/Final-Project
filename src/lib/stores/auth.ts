import { writable } from 'svelte/store';

interface User {
  id: number;
  username: string;
  role: string;
}

function createAuthStore() {
  const { subscribe, set, update } = writable<User | null>(null);

  return {
    subscribe,
    login: (user: User) => {
      set(user);
      localStorage.setItem('user', JSON.stringify(user));
    },
    logout: () => {
      set(null);
      localStorage.removeItem('user');
      window.location.href = '/';
    },
    initialize: () => {
      const storedUser = localStorage.getItem('user');
      if (storedUser) {
        set(JSON.parse(storedUser));
      }
    }
  };
}

export const auth = createAuthStore(); 
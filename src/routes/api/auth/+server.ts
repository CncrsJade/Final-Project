import { json } from '@sveltejs/kit';
import type { RequestHandler } from './$types';
import { pool } from '$lib/db';
import bcrypt from 'bcrypt';

export const POST: RequestHandler = async ({ request }) => {
    const { username, password } = await request.json();
    
    try {
        const [rows]: any = await pool.query(
            'SELECT id, username, password, role FROM users WHERE username = ?',
            [username]
        );

        if (rows.length === 0) {
            return json({ error: 'User not found' }, { status: 401 });
        }

        const user = rows[0];
        const match = await bcrypt.compare(password, user.password);

        if (!match) {
            return json({ error: 'Invalid password' }, { status: 401 });
        }

        // Don't send the password hash to the client
        delete user.password;
        
        return json({ user });
    } catch (error) {
        console.error('Login error:', error);
        return json({ error: 'Internal server error' }, { status: 500 });
    }
}; 
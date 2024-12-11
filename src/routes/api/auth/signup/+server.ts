import { json } from '@sveltejs/kit';
import type { RequestHandler } from './$types';
import { pool } from '$lib/db';
import bcrypt from 'bcrypt';

export const POST: RequestHandler = async ({ request }) => {
    const { username, password } = await request.json();
    
    try {
        // Check if username already exists
        const [existingUsers]: any = await pool.query(
            'SELECT id FROM users WHERE username = ?',
            [username]
        );

        if (existingUsers.length > 0) {
            return json({ error: 'Username already exists' }, { status: 400 });
        }

        // Hash password
        const hashedPassword = await bcrypt.hash(password, 10);

        // Insert new user
        const [result]: any = await pool.query(
            'INSERT INTO users (username, password, role) VALUES (?, ?, ?)',
            [username, hashedPassword, 'user']
        );

        const user = {
            id: result.insertId,
            username,
            role: 'user'
        };

        return json({ user });
    } catch (error) {
        console.error('Signup error:', error);
        return json({ error: 'Internal server error' }, { status: 500 });
    }
};
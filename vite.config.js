import laravel from 'laravel-vite-plugin';
import { defineConfig } from 'vite';

export default defineConfig({
    plugins: [
        laravel({
            // Tell Vite to scan these files for Tailwind classes/assets
            input: [
                'resources/css/app.css',  // Tailwind CSS entry
                'resources/js/app.js',    // JS entry (required for Vite)
            ],
            refresh: true, // Auto-refresh browser when views change
        }),
    ],
    // Optional: Fix port conflicts if 5173 is used
    server: {
        port: 5173,
    },
    resolve: {
        alias: {
            '$': 'jQuery'
        },
    },
});
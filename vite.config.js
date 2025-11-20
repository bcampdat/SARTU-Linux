import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    server: {
        host: true,           
        port: 5173,
        strictPort: true,
        allowedHosts: ['sartu_win.node', 'localhost', '127.0.0.1'],
    },
    plugins: [
        laravel({
            input: ['resources/js/app.js', 'resources/css/app.css'],
            refresh: true,
        }),
    ],
});
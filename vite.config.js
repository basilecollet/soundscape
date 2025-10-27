import {
    defineConfig
} from 'vite';
import laravel from 'laravel-vite-plugin';
import tailwindcss from "@tailwindcss/vite";

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/css/app.css',      // Portfolio styles
                'resources/css/admin.css',    // Admin styles (includes app.css + admin-enhancements.css)
                'resources/js/app.js'
            ],
            refresh: true,
        }),
        tailwindcss(),
    ],
    server: {
        cors: true,
        host: '0.0.0.0',
        strictPort: true,
        port: 5173,
        hmr: {
            host: process.env.VITE_HMR_HOST || 'localhost',
        },
    },
});

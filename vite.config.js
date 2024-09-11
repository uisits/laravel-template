import {defineConfig} from 'vite';
import laravel, {refreshPaths} from 'laravel-vite-plugin';

export default defineConfig({
    server: {
        port: 5173,
        https: {
            key: '/etc/ssl/private/wildcard_key.key',
            cert: '/etc/ssl/certs/wildcard_cert.cer',
        },
        host: 'apps.uis.edu',
    },
    plugins: [
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.js'],
            refresh: [
                ...refreshPaths,
                'app/Livewire/**',
                'app/Filament/**',
                'resources/views/**'
            ],
        }),
    ],
});

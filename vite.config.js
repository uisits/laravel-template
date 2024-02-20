import { defineConfig } from 'vite';
import laravel, { refreshPaths } from 'laravel-vite-plugin';

export default defineConfig({
    server: { 
	port: 18453,    
        https: {
		key: '/etc/ssl/private/wildcard_key.key', 
		cert: '/etc/ssl/certs/wildcard_cert.cer',
	},
        host: 'uisapps1.uis.edu', 
   },
    plugins: [
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.js'],
            refresh: [
		... refreshPaths,
		'app/Livewire/**', 
		'app/Filament/**',
	    ],
        }),
    ],
});

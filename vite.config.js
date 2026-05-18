import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/css/app.css',
                'resources/js/app.js',
            ],
            refresh: true,
        }),
    ],
    build: {
        rollupOptions: {
            input: {
                app: 'resources/css/app.css',
                appjs: 'resources/js/app.js',
                theme: 'resources/css/filament/admin/theme.css',
            },
            output: {
                assetFileNames: 'assets/[name][extname]',
            },
        },
    },
});
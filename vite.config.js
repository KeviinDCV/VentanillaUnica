import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/css/app.css',
                'resources/js/app.js',
                'resources/js/simple-navigation.js',
                'resources/js/radicacion.js',
                'resources/js/session-manager.js',
                'resources/js/reportes.js',
                'resources/js/preview.js',
                'resources/js/admin-logs.js',
                'resources/js/admin-dependencias.js',
                'resources/js/admin-trds.js',
                'resources/js/admin-departamentos.js',
                'resources/js/admin-ciudades.js',
                'resources/js/admin-tipos-solicitud.js',
                'resources/js/colombia-time.js',
                'resources/js/ciudad-departamento.js'
            ],
            refresh: true,
        }),
    ],
    server: {
        host: '127.0.0.1',
        port: 5173,
        strictPort: true,
    },
    build: {
        outDir: 'public/build',
        manifest: 'manifest.json',
        assetsDir: 'assets',
        rollupOptions: {
            input: [
                'resources/css/app.css',
                'resources/js/app.js',
                'resources/js/simple-navigation.js',
                'resources/js/radicacion.js',
                'resources/js/session-manager.js',
                'resources/js/reportes.js',
                'resources/js/preview.js',
                'resources/js/admin-logs.js',
                'resources/js/admin-dependencias.js',
                'resources/js/admin-trds.js',
                'resources/js/admin-departamentos.js',
                'resources/js/admin-ciudades.js',
                'resources/js/admin-tipos-solicitud.js',
                'resources/js/colombia-time.js',
                'resources/js/ciudad-departamento.js'
            ],
            output: {
                assetFileNames: 'assets/[name]-[hash][extname]',
                chunkFileNames: 'assets/[name]-[hash].js',
                entryFileNames: 'assets/[name]-[hash].js',
            }
        }
    },
    base: '/',
    publicDir: false
});

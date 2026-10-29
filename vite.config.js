import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import vue from '@vitejs/plugin-vue';

// Set VITE_HOST to your LAN IP (e.g. 192.168.1.10) to open the dev site from your phone
const host = process.env.VITE_HOST ?? 'localhost';
const port = process.env.VITE_PORT ?? 5173;

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/assets/scss/theme.scss',
                'resources/assets/js/app.js',
            ],
            refresh: true,
        }),
        vue(),
    ],
    server: {
        host: true,
        port: 5173,
        strictPort: true,
        origin: `http://${host}:${port}`,
        cors: {
            origin: [
                /^https?:\/\/(localhost|127\.0\.0\.1)(:\d+)?$/,
                new RegExp(`^https?://${host.replaceAll('.', '\\.')}(:\\d+)?$`),
            ],
        },
    },
    css: {
        preprocessorOptions: {
            scss: {
                // Bootstrap 4 only loads through @import and warns about its own code; this is the setup its docs give
                quietDeps: true,
                silenceDeprecations: ['import'],
            },
        },
    },
});

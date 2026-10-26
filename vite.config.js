import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import vue from '@vitejs/plugin-vue';
import { fileURLToPath } from 'node:url';

// Set VITE_HOST to your LAN IP (e.g. 192.168.1.10) to open the dev site from your phone
const host = process.env.VITE_HOST ?? 'localhost';
const port = process.env.VITE_PORT ?? 5173;

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/assets/scss/theme.scss',
                'resources/assets/js/app.js',
                'resources/assets/js/vendor/jquery-bootstrap.js',
                'resources/assets/js/vendor/datatables.js',
                'resources/assets/js/vendor/moment.js',
                'resources/assets/js/vendor/tempusdominus.js',
                'resources/assets/js/vendor/summernote.js',
            ],
            refresh: true,
        }),
        vue(),
    ],
    resolve: {
        alias: [
            { find: /^vue$/, replacement: 'vue/dist/vue.esm-bundler.js' },
            { find: /^moment$/, replacement: fileURLToPath(new URL('./node_modules/moment/moment.js', import.meta.url)) },
        ],
    },
    server: {
        host: true,
        port: 5173,
        strictPort: true,
        origin: `http://${host}:${port}`,
        cors: {
            origin: [
                /^https?:\/\/(localhost|127\.0\.0\.1|192\.168\.\d+\.\d+)(:\d+)?$/,
                new RegExp(`^https?://${host.replaceAll('.', '\\.')}(:\\d+)?$`),
            ],
        },
    },
    optimizeDeps: {
        exclude: ['axios'],
    },
});

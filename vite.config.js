import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import vue from '@vitejs/plugin-vue';
import { fileURLToPath } from 'node:url';

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
});

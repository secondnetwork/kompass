import { defineConfig ,splitVendorChunkPlugin  } from 'vite';
import laravel from 'laravel-vite-plugin';
export default defineConfig({
    plugins: [
        splitVendorChunkPlugin(),
        laravel({
            input: ['resources/js/main.js','resources/css/main.css'],
            buildDirectory: 'content',
            refresh: true,
        }),
    ],
});

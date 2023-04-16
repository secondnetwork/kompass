import { defineConfig ,splitVendorChunkPlugin  } from 'vite';
import laravel from 'laravel-vite-plugin';
import sassGlobImports from 'vite-plugin-sass-glob-import';
export default defineConfig({
    plugins: [
        splitVendorChunkPlugin(),
        sassGlobImports(),
        laravel({
            input: ['resources/js/main.js','resources/sass/main.scss'],
            buildDirectory: 'content',
            refresh: true,
        }),
    ],
});

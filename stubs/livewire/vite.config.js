import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import tailwindcss from '@tailwindcss/vite'
export default defineConfig({
    plugins: [
        tailwindcss(),
        laravel({
            input: ['resources/js/main.js','resources/css/main.css'],
            buildDirectory: 'content',
            refresh: true,
        }),
    ],
    server: {
        port: 5173,
        // strictPort: true,
        cors: true, // oder spezifische Optionen
        hmr: {
          host: 'localhost',
      },
      },
});

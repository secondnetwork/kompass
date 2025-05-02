import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import tailwindcss from '@tailwindcss/vite';


export default defineConfig({
  build: {
    // emptyOutDir: false,

    chunkSizeWarningLimit: 600, // In kilobytes
    rollupOptions: {
      output: {
  
        manualChunks(id) {
          if (id.includes('node_modules/preline')){
            return 'preline'
          }
        if (id.includes('node_modules/@nextapps-be')) {
            return 'livewire-sortable';
        }
          if(id.includes('node_modules/sortablejs')){
              return 'sortable';
          }
        if (id.includes('node_modules/editorjs')){
            return 'editorjs-lib';
        }
        if (id.includes('node_modules/alpinejs')){
            return 'alpine-lib';
        }

        if (id.includes('node_modules')) {
            return 'vendor';
        }
        if (id.includes('/alpine/')) {
           return 'alpine-components';
         }
         if (id.includes('/editorjs')) {
           return 'editorjs';
         }
         if(id.includes('./plugins/')){
            return 'plugins';
          }
     },
     compact: true,
        entryFileNames: `js/[name].[hash].js`,
        chunkFileNames: `js/[name].[hash].js`,
        assetFileNames: `css/[name].[hash].[ext]`
      }
    },
  },
  plugins: [
    tailwindcss(),
    laravel({
      input: ['resources/js/main.js', 'resources/css/kompass.css'],
      buildDirectory: 'assets/build',
      refresh: true,

      // refresh: {
      //   paths: ['resources/**', 'routes/**'],
      //   config: { delay: 300 },
      // },
    }),

  ],
  server: {
    port: 5088,
    // strictPort: true,
    cors: true, // oder spezifische Optionen
    hmr: {
      host: 'localhost',
  },
  },
});

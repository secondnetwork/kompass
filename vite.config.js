import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import tailwindcss from '@tailwindcss/vite';


export default defineConfig({
  build: {
    chunkSizeWarningLimit: 600,
    rollupOptions: {
      output: {
        compact: true,
        entryFileNames: `js/[name].[hash].js`,
        chunkFileNames: `js/[name].[hash].js`,
        assetFileNames: `css/[name].[hash].[ext]`,
        manualChunks(id, { getModuleInfo, getChunkModules }) {
          if (id.includes('node_modules/@alpinejs')) {
            return 'alpine';
          }
          if (id.includes('node_modules/livewire')) {
            return 'livewire';
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
          if (id.includes('./plugins/')) {
            return 'plugins';
          }
        }
      },
      external: ['preline', 'Sortable', 'Livewire', 'Alpine']
    },
    cssCodeSplit: true,
  },
  plugins: [
    tailwindcss(),
    laravel({
      input: [
        'resources/js/main.js', 
        'resources/js/alpine.js',
        'resources/css/kompass.css'
      ],
      buildDirectory: 'assets/build',
      refresh: true,
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

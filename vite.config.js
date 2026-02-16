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
        manualChunks(id) {
          // Reihenfolge ist wichtig: Spezifische Module ZUERST prüfen!
          
          // Tiptap Editor
          if (id.includes('node_modules/@tiptap')) {
            return 'tiptap';
          }
          
          // ApexCharts für Dashboard (nur dort benötigt)
          if (id.includes('node_modules/apexcharts')) {
            return 'charts';
          }
          
          // Alpine.js Core
          if (id.includes('node_modules/@alpinejs') || id.includes('node_modules/alpinejs')) {
            return 'alpine';
          }
          
          // Livewire
          if (id.includes('node_modules/livewire')) {
            return 'livewire';
          }
          
          // UI Libraries (Preline, etc.)
          if (id.includes('node_modules/preline')) {
            return 'ui';
          }
          
          // SortableJS (falls verwendet)
          if (id.includes('node_modules/sortablejs')) {
            return 'sortable';
          }
          
          // Projekt-spezifische Chunks
          if (id.includes('/alpine/')) {
            return 'alpine-components';
          }
          if (id.includes('./plugins/')) {
            return 'plugins';
          }
          
          // Restliche node_modules
          if (id.includes('node_modules')) {
            return 'vendor';
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

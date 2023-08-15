import { defineConfig, splitVendorChunkPlugin } from 'vite';
import laravel from 'laravel-vite-plugin';
// import sassGlobImports from 'vite-plugin-sass-glob-import';

export default defineConfig({
  build: {
    // emptyOutDir: false,
    rollupOptions: {
      output: {
        entryFileNames: `js/[name].[hash].js`,
        chunkFileNames: `js/[name].[hash].js`,
        assetFileNames: `css/[name].[hash].[ext]`
      }
    },
  },
  plugins: [
    splitVendorChunkPlugin(),
    laravel({
      input: ['resources/js/main.js', 'resources/css/kompass.css'],
      buildDirectory: 'assets/build',
      // refresh: true,
      refresh: {
        paths: ['resources/**', 'routes/**'],
        config: { delay: 300 },
      },
    }),
    
  ],
  server: {
    port: 5088,
    strictPort: true
  },
});

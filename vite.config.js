import {defineConfig} from 'vite';
import laravel from 'laravel-vite-plugin';
import vue from '@vitejs/plugin-vue';
import dotenv from 'dotenv';
import fs from 'fs';

dotenv.config();

const host = process.env.APP_URL

export default defineConfig({
  build: {
    chunkSizeWarningLimit: 1000
  },
  server: {
    hmr: {
      host: 'localhost',
    },
    watch: {
      usePolling: true,
    },
  },
  plugins: [
    laravel({
      refresh: true,
      input: [
        'resources/css/app.css',
        'resources/js/app.js',
      ],
    }),
    vue({
      template: {
        transformAssetUrls: {
          base: null,
          includeAbsolute: false,
        },
      },
    }),
  ],
  test: {
    environment: 'jsdom',
    globals: true,
  },
});









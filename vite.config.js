import { defineConfig } from 'vite'
import vue from '@vitejs/plugin-vue'
import path from 'path'

export default defineConfig({
  plugins: [vue()],
  base: './',
  resolve: {
    alias: {
      '@': path.resolve(__dirname, './src'),
    },
  },
  server: {
    port: 3000,
    open: false,
    host: true,
    proxy: {
      '^.*api\\.php': {
        target: 'http://127.0.0.1/IEEE%20AAU%20SB%20PLATFORM',
        changeOrigin: true,
        secure: false,
        rewrite: (path) => {
          const query = path.includes('?') ? path.substring(path.indexOf('?')) : '';
          return '/api.php' + query;
        }
      },
      '^.*uploads/': {
        target: 'http://127.0.0.1/IEEE%20AAU%20SB%20PLATFORM',
        changeOrigin: true,
        secure: false,
        rewrite: (path) => {
          const idx = path.indexOf('uploads/');
          return '/' + path.substring(idx);
        }
      }
    }
  }
})

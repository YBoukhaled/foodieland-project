import { defineConfig } from 'vite'
import vue from '@vitejs/plugin-vue'

// https://vitejs.dev/config/
export default defineConfig({
  plugins: [vue()],
  server: {
    port: 5173,
    host: '0.0.0.0',
    watch: {
      usePolling: true, // Required for hot reload on Windows + Docker
      interval: 1000,
    },
  },
})
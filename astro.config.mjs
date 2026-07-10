import { defineConfig } from 'astro/config';

export default defineConfig({
  site: 'https://izayoi-hiroshima.com',
  build: {
    inlineStylesheets: 'auto',
  },
  vite: {
    build: {
      cssMinify: true,
    },
    server: {
      // iCloud同期下のDesktopフォルダではFSEventsのファイル監視がハングするためポーリングに切り替え
      watch: {
        usePolling: true,
      },
    },
  },
});

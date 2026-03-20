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
  },
});

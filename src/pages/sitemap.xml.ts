import type { APIRoute } from 'astro';
import { getCollection } from 'astro:content';

const SITE = 'https://izayoi-hiroshima.com';

// 固定ページ（トレイリングスラッシュ付きでcanonicalと揃える）
const STATIC_PATHS = [
  '/',
  '/ai/',
  '/services/',
  '/products/',
  '/blog/',
  '/about/',
  '/products/rheo/',
  '/products/izayoi-hub/',
  '/products/relife/',
  '/works/',
  '/contact/',
];

export const GET: APIRoute = async () => {
  const posts = (await getCollection('blog')).filter((p) => !p.data.draft);

  const entries = [
    ...STATIC_PATHS.map((p) => ({ loc: `${SITE}${p}`, lastmod: undefined as string | undefined })),
    ...posts.map((p) => ({
      loc: `${SITE}/blog/${p.id}/`,
      lastmod: (p.data.updatedDate ?? p.data.pubDate).toISOString(),
    })),
  ];

  const xml = `<?xml version="1.0" encoding="UTF-8"?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
${entries
  .map((u) => `  <url><loc>${u.loc}</loc>${u.lastmod ? `<lastmod>${u.lastmod}</lastmod>` : ''}</url>`)
  .join('\n')}
</urlset>`;

  return new Response(xml, {
    headers: { 'Content-Type': 'application/xml; charset=utf-8' },
  });
};

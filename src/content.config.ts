import { defineCollection, z } from 'astro:content';
import { glob } from 'astro/loaders';

// ブログ／コラム記事のコレクション。
// src/content/blog/ に Markdown（.md）を追加するだけで記事が増えます。
const blog = defineCollection({
  loader: glob({ pattern: '**/*.md', base: './src/content/blog' }),
  schema: z.object({
    title: z.string(),
    description: z.string(),
    pubDate: z.coerce.date(),
    updatedDate: z.coerce.date().optional(),
    tags: z.array(z.string()).default([]),
    cover: z.string().optional(),
    author: z.string().default('IZAYOI合同会社'),
    draft: z.boolean().default(false),
  }),
});

export const collections = { blog };

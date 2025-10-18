@php($index = Storage::disk('public')->url('sitemap-index.xml'))
Sitemap: {{ config('app.url') }}/{{ ltrim(parse_url($index, PHP_URL_PATH) ?? 'storage/sitemap-index.xml', '/') }}

User-agent: *
Allow: /

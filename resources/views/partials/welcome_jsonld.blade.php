<script type="application/ld+json">
{!! json_encode([
    '@context' => 'https://schema.org',
    '@type' => 'WebSite',
    'url' => config('app.url', url('/')),
    'name' => config('app.name', 'sar7ne'),
    'potentialAction' => [
        '@type' => 'SearchAction',
        'target' => route('explore').'?q={search_term_string}',
        'query-input' => 'required name=search_term_string',
    ],
], JSON_UNESCAPED_SLASHES|JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE) !!}
</script>


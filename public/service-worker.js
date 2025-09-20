self.addEventListener('install', event => {
  event.waitUntil(
    caches.open('sar7ne-v1').then(cache => {
      return cache.addAll([
        '/',
        '/manifest.json',
        '/favicon.ico',
        '/favicon.svg',
        '/apple-touch-icon.png'
      ]);
    })
  );
});

self.addEventListener('fetch', event => {
  event.respondWith(
    caches.match(event.request).then(response => {
      return response || fetch(event.request);
    })
  );
});
{
  "name": "Sar7ne",
  "short_name": "Sar7ne",
  "start_url": "/",
  "display": "standalone",
  "background_color": "#ffffff",
  "theme_color": "#0d6efd",
  "description": "A Laravel-based PWA app.",
  "icons": [
    {
      "src": "/apple-touch-icon.png",
      "sizes": "180x180",
      "type": "image/png"
    },
    {
      "src": "/favicon.svg",
      "sizes": "any",
      "type": "image/svg+xml"
    }
  ]
}


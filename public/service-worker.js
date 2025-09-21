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
// Basic activate handler to clean old caches
self.addEventListener('activate', event => {
  const currentCache = 'sar7ne-v1';
  event.waitUntil(
    caches.keys().then(keys =>
      Promise.all(keys.filter(k => k !== currentCache).map(k => caches.delete(k)))
    )
  );
});

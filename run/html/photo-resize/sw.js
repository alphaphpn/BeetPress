self.addEventListener('install', (e) => {
  console.log('Service Worker: Installed');
});

self.addEventListener('fetch', (e) => {
  // This handles offline capabilities if needed later
  e.respondWith(fetch(e.request));
});
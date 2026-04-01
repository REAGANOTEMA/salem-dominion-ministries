// Service Worker for Salem Dominion Ministries PWA
const CACHE_NAME = 'salem-dominion-v1.0.3';
const STATIC_CACHE = 'salem-static-v1.0.3';
const DYNAMIC_CACHE = 'salem-dynamic-v1.0.3';

// Install event - only cache what exists
self.addEventListener('install', (event) => {
  console.log('🔧 Service Worker: Installing');
  // Skip waiting immediately to avoid stale workers
  self.skipWaiting();
});

// Activate event - clean up old caches
self.addEventListener('activate', (event) => {
  console.log('🚀 Service Worker: Activating');
  event.waitUntil(
    caches.keys()
      .then((cacheNames) => {
        return Promise.all(
          cacheNames.map((cache) => {
            if (cache !== STATIC_CACHE && cache !== DYNAMIC_CACHE) {
              console.log('🗑️ Service Worker: Deleting old cache:', cache);
              return caches.delete(cache);
            }
          })
        );
      })
      .then(() => self.clients.claim())
  );
});

// Fetch event
self.addEventListener('fetch', (event) => {
  const { request } = event;
  
  // Skip non-HTTP requests
  if (!request.url.startsWith('http')) {
    return;
  }

  // Handle API requests
  if (request.url.includes('/api.php') || request.url.includes('/api/')) {
    event.respondWith(
      fetch(request)
        .then((response) => {
          if (response && response.ok) {
            try {
              const responseClone = response.clone();
              caches.open(DYNAMIC_CACHE).then((cache) => {
                cache.put(request, responseClone);
              });
            } catch (e) {
              // Ignore caching errors
            }
          }
          return response;
        })
        .catch(() => {
          return new Response(JSON.stringify({
            success: false,
            message: 'You are offline. Please check your connection.'
          }), {
            status: 503,
            headers: { 'Content-Type': 'application/json' }
          });
        })
    );
    return;
  }

  // Network first for documents, cache first for static assets
  if (request.destination === 'document') {
    event.respondWith(
      fetch(request)
        .then((response) => {
          if (response && response.ok) {
            try {
              const responseClone = response.clone();
              caches.open(DYNAMIC_CACHE).then((cache) => {
                cache.put(request, responseClone);
              });
            } catch (e) {
              // Ignore caching errors
            }
          }
          return response;
        })
        .catch(() => {
          return caches.match(request).then((cached) => {
            return cached || new Response('Offline', { status: 503, headers: { 'Content-Type': 'text/html' } });
          });
        })
    );
    return;
  }

  // Cache first for static assets
  event.respondWith(
    caches.match(request)
      .then((cachedResponse) => {
        if (cachedResponse) {
          return cachedResponse;
        }

        return fetch(request)
          .then((response) => {
            if (!response || response.status !== 200) {
              return response;
            }

            try {
              const responseToCache = response.clone();
              caches.open(STATIC_CACHE).then((cache) => {
                cache.put(request, responseToCache);
              });
            } catch (e) {
              // Ignore caching errors
            }

            return response;
          })
          .catch(() => {
            return new Response('Offline', { status: 503 });
          });
      })
      .catch(() => {
        return new Response('Offline', { status: 503 });
      })
  );
});

// Push notifications
self.addEventListener('push', (event) => {
  const options = {
    body: event.data ? event.data.text() : 'New update from Salem Dominion Ministries',
    icon: '/icons/icon-192x192.svg',
    badge: '/icons/icon-72x72.svg',
  };
  event.waitUntil(
    self.registration.showNotification('Salem Dominion Ministries', options)
  );
});

// Notification click handler
self.addEventListener('notificationclick', (event) => {
  event.notification.close();
  event.waitUntil(
    clients.matchAll().then((clientList) => {
      for (const client of clientList) {
        if (client.url === '/' && 'focus' in client) {
          return client.focus();
        }
      }
      if (clients.openWindow) {
        return clients.openWindow('/');
      }
    })
  );
});

// Message handling
self.addEventListener('message', (event) => {
  if (event.data && event.data.type === 'SKIP_WAITING') {
    event.waitUntil(self.skipWaiting());
  }
});
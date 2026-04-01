// Service Worker for Salem Dominion Ministries PWA
const CACHE_NAME = 'salem-dominion-v1.0.4';
const STATIC_CACHE = 'salem-static-v1.0.4';

// Install event - skip waiting immediately
self.addEventListener('install', (event) => {
  console.log('🔧 Service Worker: Installing');
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
            if (cache !== STATIC_CACHE) {
              console.log('🗑️ Service Worker: Deleting old cache:', cache);
              return caches.delete(cache);
            }
          })
        );
      })
      .then(() => self.clients.claim())
  );
});

// Fetch event - only cache static assets, bypass API requests
self.addEventListener('fetch', (event) => {
  const url = new URL(event.request.url);

  // Skip API requests completely - let them go directly to backend
  if (url.origin.includes('localhost:5000') || 
      url.pathname.includes('/api') ||
      url.pathname.includes('/api.php') ||
      url.search.includes('route=')) {
    console.log('⏭️ Service Worker: Skipping API request:', url.href);
    return;
  }

  // Only cache GET requests for static assets
  if (event.request.method !== 'GET') {
    return;
  }

  // Skip non-HTTP requests
  if (!url.protocol.startsWith('http')) {
    return;
  }

  event.respondWith(
    caches.match(event.request)
      .then((cachedResponse) => {
        if (cachedResponse) {
          console.log('💾 Service Worker: Serving from cache:', url.pathname);
          return cachedResponse;
        }

        // Fetch from network
        return fetch(event.request)
          .then((response) => {
            // Don't cache non-ok responses
            if (!response || response.status !== 200) {
              return response;
            }

            // Clone the response
            const responseToCache = response.clone();

            // Cache static assets only
            if (event.request.destination === 'style' || 
                event.request.destination === 'script' || 
                event.request.destination === 'image' ||
                event.request.destination === 'font') {
              caches.open(STATIC_CACHE)
                .then((cache) => {
                  cache.put(event.request, responseToCache);
                });
            }

            return response;
          })
          .catch(() => {
            // Return offline response only for static assets
            if (event.request.destination === 'document') {
              return caches.match('/offline.html');
            }
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
// Service Worker for Salem Dominion Ministries PWA
const CACHE_NAME = 'salem-dominion-v1.0.0';
const STATIC_CACHE = 'salem-static-v1.0.0';
const DYNAMIC_CACHE = 'salem-dynamic-v1.0.0';

// Subdirectory base path
const BASE_PATH = '/salem-dominion-ministries';

// Files to cache for offline functionality
const STATIC_ASSETS = [
  BASE_PATH + '/',
  BASE_PATH + '/index.html',
  BASE_PATH + '/manifest.json',
  BASE_PATH + '/offline.html',
  // Church logo icons (SVG for better quality)
  BASE_PATH + '/icons/icon-72x72.svg',
  BASE_PATH + '/icons/icon-96x96.svg',
  BASE_PATH + '/icons/icon-128x128.svg',
  BASE_PATH + '/icons/icon-144x144.svg',
  BASE_PATH + '/icons/icon-152x152.svg',
  BASE_PATH + '/icons/icon-192x192.svg',
  BASE_PATH + '/icons/icon-384x384.svg',
  BASE_PATH + '/icons/icon-512x512.svg'
];

// Install event - cache static assets
self.addEventListener('install', (event) => {
  console.log('🔧 Service Worker: Installing');
  
  event.waitUntil(
    caches.open(STATIC_CACHE)
      .then((cache) => {
        console.log('📦 Service Worker: Caching static assets');
        return cache.addAll(STATIC_ASSETS);
      })
      .then(() => self.skipWaiting())
  );
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

// Fetch event - serve from cache when offline
self.addEventListener('fetch', (event) => {
  const { request } = event;
  const url = new URL(request.url);
  
  // Skip non-HTTP requests
  if (!request.url.startsWith('http')) {
    return;
  }
  
  // Handle API requests
  if (url.pathname.startsWith('/api/')) {
    event.respondWith(
      fetch(request)
        .then((response) => {
          // Cache successful API responses
          if (response.ok) {
            const responseClone = response.clone();
            caches.open(DYNAMIC_CACHE)
              .then((cache) => cache.put(request, responseClone));
          }
          return response;
        })
        .catch(() => {
          // Try to serve from cache if network fails
          return caches.match(request);
        })
    );
    return;
  }
  
  // Handle static assets and pages
  event.respondWith(
    caches.match(request)
      .then((response) => {
        // Serve from cache if available
        if (response) {
          return response;
        }
        
        // Fetch from network
        return fetch(request)
          .then((response) => {
            // Cache new static assets
            if (response.ok && request.destination === 'document') {
              const responseClone = response.clone();
              caches.open(DYNAMIC_CACHE)
                .then((cache) => cache.put(request, responseClone));
            }
            return response;
          })
          .catch(() => {
            // Serve offline page for navigation requests
            if (request.destination === 'document') {
              return caches.match('/offline.html');
            }
          });
      })
  );
});

// Background sync for offline actions
self.addEventListener('sync', (event) => {
  console.log('🔄 Service Worker: Background sync', event.tag);
  
  if (event.tag === 'sync-prayer-requests') {
    event.waitUntil(syncPrayerRequests());
  }
  
  if (event.tag === 'sync-donations') {
    event.waitUntil(syncDonations());
  }
});

// Push notifications
self.addEventListener('push', (event) => {
  console.log('📢 Service Worker: Push notification received');
  
  const options = {
    body: event.data ? event.data.text() : 'New update from Salem Dominion Ministries',
    icon: BASE_PATH + '/icons/icon-192x192.svg',
    badge: BASE_PATH + '/icons/icon-72x72.svg',
    vibrate: [100, 50, 100],
    data: {
      dateOfArrival: Date.now(),
      primaryKey: 1
    },
    actions: [
      {
        action: 'explore',
        title: 'Open App',
        icon: BASE_PATH + '/icons/icon-72x72.svg'
      },
      {
        action: 'close',
        title: 'Close',
        icon: BASE_PATH + '/icons/icon-72x72.svg'
      }
    ]
  };
  
  event.waitUntil(
    self.registration.showNotification('Salem Dominion Ministries', options)
  );
});

// Notification click handler
self.addEventListener('notificationclick', (event) => {
  console.log('👆 Service Worker: Notification click received');
  
  event.notification.close();
  
  if (event.action === 'explore') {
    event.waitUntil(
      clients.openWindow(BASE_PATH + '/')
    );
  } else if (event.action === 'close') {
    // Just close the notification
  } else {
    // Default action - open app
    event.waitUntil(
      clients.matchAll().then((clientList) => {
        for (const client of clientList) {
          if (client.url === BASE_PATH + '/' && 'focus' in client) {
            return client.focus();
          }
        }
        if (clients.openWindow) {
          return clients.openWindow(BASE_PATH + '/');
        }
      })
    );
  }
});

// Sync offline prayer requests
async function syncPrayerRequests() {
  try {
    const offlinePrayers = await getOfflineData('prayer-requests');
    
    for (const prayer of offlinePrayers) {
      try {
        const response = await fetch('/api/prayers', {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json',
            'Authorization': `Bearer ${prayer.token}`
          },
          body: JSON.stringify(prayer.data)
        });
        
        if (response.ok) {
          await removeOfflineData('prayer-requests', prayer.id);
          console.log('✅ Synced prayer request:', prayer.id);
        }
      } catch (error) {
        console.error('❌ Failed to sync prayer request:', error);
      }
    }
  } catch (error) {
    console.error('❌ Sync prayer requests failed:', error);
  }
}

// Sync offline donations
async function syncDonations() {
  try {
    const offlineDonations = await getOfflineData('donations');
    
    for (const donation of offlineDonations) {
      try {
        const response = await fetch('/api/donations', {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json',
            'Authorization': `Bearer ${donation.token}`
          },
          body: JSON.stringify(donation.data)
        });
        
        if (response.ok) {
          await removeOfflineData('donations', donation.id);
          console.log('✅ Synced donation:', donation.id);
        }
      } catch (error) {
        console.error('❌ Failed to sync donation:', error);
      }
    }
  } catch (error) {
    console.error('❌ Sync donations failed:', error);
  }
}

// IndexedDB helpers for offline storage
async function getOfflineData(storeName) {
  return new Promise((resolve, reject) => {
    const request = indexedDB.open('salem-offline-db', 1);
    
    request.onerror = () => reject(request.error);
    request.onsuccess = () => {
      const db = request.result;
      const transaction = db.transaction(storeName, 'readonly');
      const store = transaction.objectStore(storeName);
      const getAll = store.getAll();
      
      getAll.onsuccess = () => resolve(getAll.result);
      getAll.onerror = () => reject(getAll.error);
    };
    
    request.onupgradeneeded = () => {
      const db = request.result;
      if (!db.objectStoreNames.contains(storeName)) {
        db.createObjectStore(storeName, { keyPath: 'id' });
      }
    };
  });
}

async function removeOfflineData(storeName, id) {
  return new Promise((resolve, reject) => {
    const request = indexedDB.open('salem-offline-db', 1);
    
    request.onerror = () => reject(request.error);
    request.onsuccess = () => {
      const db = request.result;
      const transaction = db.transaction(storeName, 'readwrite');
      const store = transaction.objectStore(storeName);
      const deleteRequest = store.delete(id);
      
      deleteRequest.onsuccess = () => resolve();
      deleteRequest.onerror = () => reject(deleteRequest.error);
    };
  });
}

// Message handling for real-time updates
self.addEventListener('message', (event) => {
  console.log('📨 Service Worker: Message received', event.data);
  
  if (event.data && event.data.type === 'CACHE_UPDATED') {
    // Notify clients about cache updates
    event.waitUntil(
      clients.matchAll().then((clientList) => {
        clientList.forEach((client) => {
          client.postMessage({
            type: 'CACHE_UPDATED',
            url: event.data.url
          });
        });
      })
    );
  }
});

// Periodic background sync
self.addEventListener('periodicsync', (event) => {
  console.log('⏰ Service Worker: Periodic sync', event.tag);
  
  if (event.tag === 'update-content') {
    event.waitUntil(updateContent());
  }
});

// Update content in background
async function updateContent() {
  try {
    // Check for new content
    const response = await fetch('/api/health');
    
    if (response.ok) {
      // Notify clients about new content
      clients.matchAll().then((clientList) => {
        clientList.forEach((client) => {
          client.postMessage({
            type: 'NEW_CONTENT_AVAILABLE'
          });
        });
      });
    }
  } catch (error) {
    console.error('❌ Content update failed:', error);
  }
}

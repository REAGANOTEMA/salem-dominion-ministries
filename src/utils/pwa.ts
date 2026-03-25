// Progressive Web App (PWA) utilities
export class PWAManager {
  private swRegistration: ServiceWorkerRegistration | null = null;
  private isOnline: boolean = navigator.onLine;

  constructor() {
    this.setupEventListeners();
  }

  // Register service worker
  async registerServiceWorker(): Promise<boolean> {
    if (!('serviceWorker' in navigator)) {
      console.warn('Service Worker not supported');
      return false;
    }

    try {
      const registration = await navigator.serviceWorker.register('/sw.js', {
        scope: '/'
      });

      this.swRegistration = registration;
      console.log('✅ Service Worker registered successfully');

      // Check for updates
      registration.addEventListener('updatefound', () => {
        const newWorker = registration.installing;
        if (newWorker) {
          newWorker.addEventListener('statechange', () => {
            if (newWorker.state === 'installed' && navigator.serviceWorker.controller) {
              this.showUpdateAvailable();
            }
          });
        }
      });

      return true;
    } catch (error) {
      console.error('❌ Service Worker registration failed:', error);
      return false;
    }
  }

  // Setup event listeners for online/offline status
  private setupEventListeners(): void {
    window.addEventListener('online', () => {
      this.isOnline = true;
      this.showConnectionStatus(true);
      this.syncOfflineData();
    });

    window.addEventListener('offline', () => {
      this.isOnline = false;
      this.showConnectionStatus(false);
    });

    // Listen for messages from service worker
    navigator.serviceWorker.addEventListener('message', (event) => {
      this.handleServiceWorkerMessage(event.data);
    });
  }

  // Handle messages from service worker
  private handleServiceWorkerMessage(data: any): void {
    switch (data.type) {
      case 'CACHE_UPDATED':
        this.showNotification('Content updated', 'New content is available');
        break;
      case 'NEW_CONTENT_AVAILABLE':
        this.showUpdateAvailable();
        break;
      default:
        console.log('Unknown message type:', data.type);
    }
  }

  // Show update available notification
  private showUpdateAvailable(): void {
    if (confirm('New version available! Would you like to update?')) {
      this.updateApp();
    }
  }

  // Update the application
  private updateApp(): void {
    if (this.swRegistration) {
      this.swRegistration.waiting?.postMessage({ type: 'SKIP_WAITING' });
      window.location.reload();
    }
  }

  // Show connection status
  private showConnectionStatus(online: boolean): void {
    const statusElement = document.getElementById('connection-status');
    if (statusElement) {
      statusElement.textContent = online ? 'Online' : 'Offline';
      statusElement.className = online ? 'text-green-500' : 'text-red-500';
    }

    if (!online) {
      this.showNotification('Offline Mode', 'You are currently offline. Some features may be limited.');
    } else {
      this.showNotification('Back Online', 'Connection restored!');
    }
  }

  // Show notification
  private showNotification(title: string, body: string): void {
    if ('Notification' in window && Notification.permission === 'granted') {
      new Notification(title, {
        body,
        icon: '/icons/icon-192x192.png',
        badge: '/icons/badge-72x72.png'
      });
    } else {
      // Fallback to custom notification
      console.log(`${title}: ${body}`);
    }
  }

  // Request notification permission
  async requestNotificationPermission(): Promise<boolean> {
    if (!('Notification' in window)) {
      console.warn('Notifications not supported');
      return false;
    }

    if (Notification.permission === 'granted') {
      return true;
    }

    if (Notification.permission !== 'denied') {
      const permission = await Notification.requestPermission();
      return permission === 'granted';
    }

    return false;
  }

  // Sync offline data when back online
  private async syncOfflineData(): Promise<void> {
    if ('serviceWorker' in navigator && this.swRegistration) {
      // Trigger background sync
      try {
        await this.swRegistration.sync.register('sync-prayer-requests');
        await this.swRegistration.sync.register('sync-donations');
        console.log('🔄 Background sync triggered');
      } catch (error) {
        console.log('Background sync not supported:', error);
      }
    }
  }

  // Store data for offline use
  async storeOfflineData(key: string, data: any): Promise<void> {
    try {
      const db = await this.openDB();
      const transaction = db.transaction(['offline'], 'readwrite');
      const store = transaction.objectStore('offline');
      await store.put({ id: key, data, timestamp: Date.now() });
    } catch (error) {
      console.error('Error storing offline data:', error);
    }
  }

  // Get offline data
  async getOfflineData(key: string): Promise<any> {
    try {
      const db = await this.openDB();
      const transaction = db.transaction(['offline'], 'readonly');
      const store = transaction.objectStore('offline');
      const result = await store.get(key);
      return result?.data || null;
    } catch (error) {
      console.error('Error getting offline data:', error);
      return null;
    }
  }

  // Open IndexedDB
  private openDB(): Promise<IDBDatabase> {
    return new Promise((resolve, reject) => {
      const request = indexedDB.open('salem-pwa-db', 1);
      
      request.onerror = () => reject(request.error);
      request.onsuccess = () => resolve(request.result);
      
      request.onupgradeneeded = () => {
        const db = request.result;
        if (!db.objectStoreNames.contains('offline')) {
          db.createObjectStore('offline', { keyPath: 'id' });
        }
      };
    });
  }

  // Check if app is installed (PWA)
  isAppInstalled(): boolean {
    return window.matchMedia('(display-mode: standalone)').matches ||
           (window.navigator as any).standalone === true;
  }

  // Install prompt handler
  private deferredPrompt: any = null;

  setupInstallPrompt(): void {
    window.addEventListener('beforeinstallprompt', (e) => {
      e.preventDefault();
      this.deferredPrompt = e;
      this.showInstallButton();
    });
  }

  // Show install button
  private showInstallButton(): void {
    const installButton = document.getElementById('install-app-btn');
    if (installButton) {
      installButton.style.display = 'block';
      installButton.addEventListener('click', () => this.installApp());
    }
  }

  // Install the app
  async installApp(): Promise<boolean> {
    if (!this.deferredPrompt) {
      return false;
    }

    try {
      this.deferredPrompt.prompt();
      const { outcome } = await this.deferredPrompt.userChoice;
      
      if (outcome === 'accepted') {
        console.log('✅ App installed successfully');
        return true;
      } else {
        console.log('❌ App installation declined');
        return false;
      }
    } catch (error) {
      console.error('Error installing app:', error);
      return false;
    } finally {
      this.deferredPrompt = null;
    }
  }

  // Get connection status
  getConnectionStatus(): boolean {
    return this.isOnline;
  }

  // Share content
  async shareContent(title: string, text: string, url: string): Promise<boolean> {
    if (navigator.share) {
      try {
        await navigator.share({ title, text, url });
        return true;
      } catch (error) {
        console.log('Share cancelled or failed:', error);
        return false;
      }
    } else {
      // Fallback - copy to clipboard
      try {
        await navigator.clipboard.writeText(`${title}: ${text} ${url}`);
        this.showNotification('Link Copied', 'Content link copied to clipboard');
        return true;
      } catch (error) {
        console.error('Failed to copy to clipboard:', error);
        return false;
      }
    }
  }
}

// Export singleton instance
export const pwaManager = new PWAManager();

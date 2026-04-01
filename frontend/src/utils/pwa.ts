// Progressive Web App (PWA) utilities

// Interface for service worker messages
interface ServiceWorkerMessage {
  type: string;
  [key: string]: unknown;
}

// Interface for install prompt event
interface BeforeInstallPromptEvent extends Event {
  readonly platforms: string[];
  readonly userChoice: Promise<{
    outcome: 'accepted' | 'dismissed';
    platform: string;
  }>;
  prompt(): Promise<void>;
}

// Interface for offline data
interface OfflineDataItem {
  id: string;
  data: unknown;
  timestamp: number;
}

export class PWAManager {
  private swRegistration: ServiceWorkerRegistration | null = null;
  private isOnline: boolean = navigator.onLine;
  private deferredPrompt: BeforeInstallPromptEvent | null = null;

  constructor() {
    this.setupEventListeners();
  }

  /** ---------------------------
   * Service Worker Registration
   * --------------------------- */
  async registerServiceWorker(): Promise<boolean> {
    if (!('serviceWorker' in navigator)) {
      console.warn('Service Worker not supported');
      return false;
    }

    try {
      const registration = await navigator.serviceWorker.register('/salem-dominion-ministries/sw.js', { scope: '/salem-dominion-ministries/' });
      this.swRegistration = registration;
      console.log('✅ Service Worker registered successfully');

      // Handle updates
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

  /** ---------------------------
   * Event Listeners
   * --------------------------- */
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

    if ('serviceWorker' in navigator) {
      navigator.serviceWorker.addEventListener('message', (event) => {
        this.handleServiceWorkerMessage(event.data);
      });
    }

    this.setupInstallPrompt();
  }

  /** ---------------------------
   * Service Worker Messages
   * --------------------------- */
  private handleServiceWorkerMessage(data: ServiceWorkerMessage): void {
    if (!data || !data.type) return;
    
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

  /** ---------------------------
   * Update Management
   * --------------------------- */
  private showUpdateAvailable(): void {
    if (confirm('New version available! Would you like to update?')) {
      this.updateApp();
    }
  }

  private updateApp(): void {
    if (this.swRegistration) {
      this.swRegistration.waiting?.postMessage({ type: 'SKIP_WAITING' });
      window.location.reload();
    }
  }

  /** ---------------------------
   * Connection Status
   * --------------------------- */
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

  /** ---------------------------
   * Notifications
   * --------------------------- */
  private showNotification(title: string, body: string): void {
    if ('Notification' in window && Notification.permission === 'granted') {
      new Notification(title, {
        body,
        icon: '/salem-dominion-ministries/icons/icon-192x192.svg',
        badge: '/salem-dominion-ministries/icons/icon-72x72.svg',
      });
    } else {
      console.log(`${title}: ${body}`);
    }
  }

  async requestNotificationPermission(): Promise<boolean> {
    if (!('Notification' in window)) return false;
    if (Notification.permission === 'granted') return true;
    if (Notification.permission !== 'denied') {
      const permission = await Notification.requestPermission();
      return permission === 'granted';
    }
    return false;
  }

  /** ---------------------------
   * Offline Data & Sync
   * --------------------------- */
  private async syncOfflineData(): Promise<void> {
    if ('serviceWorker' in navigator && this.swRegistration?.sync) {
      try {
        await this.swRegistration.sync.register('sync-prayer-requests');
        await this.swRegistration.sync.register('sync-donations');
        console.log('🔄 Background sync triggered');
      } catch {
        console.log('Background sync not supported');
      }
    }
  }

  async storeOfflineData(key: string, data: OfflineDataItem['data']): Promise<void> {
    try {
      const db = await this.openDB();
      const tx = db.transaction(['offline'], 'readwrite');
      const store = tx.objectStore('offline');
      const item: OfflineDataItem = { id: key, data, timestamp: Date.now() };
      store.put(item);
      await new Promise<void>((resolve, reject) => {
        tx.oncomplete = () => resolve();
        tx.onerror = () => reject(tx.error);
      });
    } catch {
      console.error('Error storing offline data');
    }
  }

  async getOfflineData(key: string): Promise<unknown> {
    try {
      const db = await this.openDB();
      const tx = db.transaction(['offline'], 'readonly');
      const store = tx.objectStore('offline');
      const request = store.get(key);
      return new Promise((resolve) => {
        request.onsuccess = () => {
          const result = request.result as OfflineDataItem | undefined;
          resolve(result?.data || null);
        };
        request.onerror = () => {
          console.error('Error getting offline data');
          resolve(null);
        };
      });
    } catch {
      console.error('Error getting offline data');
      return null;
    }
  }

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

  /** ---------------------------
   * PWA Installation
   * --------------------------- */
  isAppInstalled(): boolean {
    return window.matchMedia('(display-mode: standalone)').matches || 
           ((window.navigator as { standalone?: boolean }).standalone === true) ||
           document.referrer.includes('android-app://');
  }

  setupInstallPrompt(): void {
    window.addEventListener('beforeinstallprompt', (e: Event) => {
      e.preventDefault();
      this.deferredPrompt = (e as BeforeInstallPromptEvent);
      this.showInstallButton();
    });
  }

  private showInstallButton(): void {
    const btn = document.getElementById('install-app-btn');
    if (!btn) return;
    btn.style.display = 'block';
    btn.addEventListener('click', () => this.installApp());
  }

  async installApp(): Promise<boolean> {
    if (!this.deferredPrompt) return false;
    try {
      this.deferredPrompt.prompt();
      const { outcome } = await this.deferredPrompt.userChoice;
      return outcome === 'accepted';
    } catch {
      return false;
    } finally {
      this.deferredPrompt = null;
    }
  }

  /** ---------------------------
   * Connection & Sharing
   * --------------------------- */
  getConnectionStatus(): boolean {
    return this.isOnline;
  }

  async shareContent(title: string, text: string, url: string): Promise<boolean> {
    if (navigator.share) {
      try {
        await navigator.share({ title, text, url });
        return true;
      } catch {
        return false;
      }
    } else {
      try {
        await navigator.clipboard.writeText(`${title}: ${text} ${url}`);
        this.showNotification('Link Copied', 'Content link copied to clipboard');
        return true;
      } catch {
        return false;
      }
    }
  }

  /** ---------------------------
   * PWA Features
   * --------------------------- */
  async checkPWAFeatures(): Promise<{
    installable: boolean;
    installed: boolean;
    notifications: boolean;
    offline: boolean;
  }> {
    const features = {
      installable: 'beforeinstallprompt' in window,
      installed: this.isAppInstalled(),
      notifications: 'Notification' in window,
      offline: 'serviceWorker' in navigator
    };

    console.log('PWA Features:', features);
    return features;
  }

  /** ---------------------------
   * Cache Management
   * --------------------------- */
  async clearCache(): Promise<void> {
    if ('caches' in window) {
      try {
        const cacheNames = await caches.keys();
        await Promise.all(
          cacheNames.map(cacheName => caches.delete(cacheName))
        );
        console.log('✅ All caches cleared');
      } catch (error) {
        console.error('❌ Error clearing caches:', error);
      }
    }
  }

  /** ---------------------------
   * Update Check
   * --------------------------- */
  async checkForUpdates(): Promise<boolean> {
    if (!this.swRegistration) return false;
    
    try {
      await this.swRegistration.update();
      return true;
    } catch {
      return false;
    }
  }
}

// Export singleton instance
export const pwaManager = new PWAManager();
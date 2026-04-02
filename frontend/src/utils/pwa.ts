// Progressive Web App (PWA) utilities for Salem Dominion Ministries

// Interface for install prompt event
interface BeforeInstallPromptEvent extends Event {
  readonly platforms: string[];
  readonly userChoice: Promise<{
    outcome: 'accepted' | 'dismissed';
    platform: string;
  }>;
  prompt(): Promise<void>;
}

export class PWAManager {
  private swRegistration: ServiceWorkerRegistration | null = null;
  private isOnline: boolean = navigator.onLine;
  private deferredPrompt: BeforeInstallPromptEvent | null = null;
  private installPromptHandled: boolean = false;

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
      const registration = await navigator.serviceWorker.register('/salem-dominion-ministries/sw.js', { 
        scope: '/salem-dominion-ministries/' 
      });
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

    // Setup install prompt handler - don't prevent default yet
    this.setupInstallPrompt();
  }

  /** ---------------------------
   * Service Worker Messages
   * --------------------------- */
  private handleServiceWorkerMessage(data: { type: string }): void {
    if (!data || !data.type) return;
    
    switch (data.type) {
      case 'CACHE_UPDATED':
        this.showNotification('Content updated', 'New content is available');
        break;
      case 'NEW_CONTENT_AVAILABLE':
        this.showUpdateAvailable();
        break;
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
   * PWA Installation
   * --------------------------- */
  isAppInstalled(): boolean {
    return window.matchMedia('(display-mode: standalone)').matches || 
           ((window.navigator as { standalone?: boolean }).standalone === true);
  }

  setupInstallPrompt(): void {
    // Listen for beforeinstallprompt event
    window.addEventListener('beforeinstallprompt', (e: Event) => {
      // Prevent the mini-infobar from appearing on mobile
      e.preventDefault();
      // Store the event for later use
      this.deferredPrompt = e as BeforeInstallPromptEvent;
      // Show our custom install button
      this.showInstallButton();
      console.log('📱 PWA install prompt ready');
    });

    // Listen for app installed event
    window.addEventListener('appinstalled', () => {
      console.log('🎉 PWA installed successfully');
      this.deferredPrompt = null;
      this.hideInstallButton();
    });
  }

  private showInstallButton(): void {
    const btn = document.getElementById('install-app-btn');
    if (btn) {
      btn.classList.remove('hidden');
      btn.style.display = 'block';
      btn.addEventListener('click', () => this.installApp());
    }
  }

  private hideInstallButton(): void {
    const btn = document.getElementById('install-app-btn');
    if (btn) {
      btn.classList.add('hidden');
      btn.style.display = 'none';
    }
  }

  async installApp(): Promise<boolean> {
    if (!this.deferredPrompt) {
      console.warn('No install prompt available');
      return false;
    }

    if (this.installPromptHandled) {
      console.warn('Install prompt already handled');
      return false;
    }

    try {
      // Show the install prompt
      this.deferredPrompt.prompt();
      this.installPromptHandled = true;
      
      // Wait for the user's response
      const { outcome } = await this.deferredPrompt.userChoice;
      
      if (outcome === 'accepted') {
        console.log('✅ User accepted the install prompt');
      } else {
        console.log('❌ User dismissed the install prompt');
      }
      
      // Clear the deferred prompt
      this.deferredPrompt = null;
      this.hideInstallButton();
      
      return outcome === 'accepted';
    } catch (error) {
      console.error('Error during install prompt:', error);
      return false;
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
   * PWA Features Check
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
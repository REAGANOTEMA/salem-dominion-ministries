// Progressive Web App Installation Script
class PWAInstaller {
    constructor() {
        this.deferredPrompt = null;
        this.installButton = null;
        this.init();
    }

    init() {
        // Listen for installation prompt
        window.addEventListener('beforeinstallprompt', (e) => {
            e.preventDefault();
            this.deferredPrompt = e;
            this.showInstallButton();
        });

        // Listen for app installed
        window.addEventListener('appinstalled', () => {
            this.hideInstallButton();
            this.showInstalledMessage();
        });

        // Check if app is already installed
        if (window.matchMedia('(display-mode: standalone)').matches) {
            this.hideInstallButton();
        }

        // Create install button
        this.createInstallButton();
    }

    createInstallButton() {
        // Create install button
        this.installButton = document.createElement('div');
        this.installButton.id = 'pwa-install-button';
        this.installButton.innerHTML = `
            <div class="pwa-install-content">
                <div class="pwa-install-icon">
                    <i class="fas fa-download"></i>
                </div>
                <div class="pwa-install-text">
                    <div class="pwa-install-title">Install App</div>
                    <div class="pwa-install-desc">Get our app on your device</div>
                </div>
                <div class="pwa-install-close">
                    <i class="fas fa-times"></i>
                </div>
            </div>
        `;

        // Add styles
        this.installButton.style.cssText = `
            position: fixed;
            bottom: 20px;
            right: 20px;
            background: linear-gradient(135deg, #16a34a 0%, #15803d 100%);
            color: white;
            padding: 15px 20px;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(22, 163, 74, 0.3);
            cursor: pointer;
            z-index: 9999;
            transform: translateY(100px);
            transition: all 0.3s ease;
            max-width: 300px;
        `;

        // Add CSS for button content
        const style = document.createElement('style');
        style.textContent = `
            .pwa-install-content {
                display: flex;
                align-items: center;
                gap: 15px;
            }
            
            .pwa-install-icon {
                font-size: 1.5rem;
                flex-shrink: 0;
            }
            
            .pwa-install-text {
                flex: 1;
            }
            
            .pwa-install-title {
                font-weight: 600;
                font-size: 0.9rem;
                margin-bottom: 2px;
            }
            
            .pwa-install-desc {
                font-size: 0.8rem;
                opacity: 0.9;
            }
            
            .pwa-install-close {
                cursor: pointer;
                opacity: 0.7;
                transition: opacity 0.3s ease;
                flex-shrink: 0;
            }
            
            .pwa-install-close:hover {
                opacity: 1;
            }
            
            #pwa-install-button:hover {
                transform: translateY(-5px);
                box-shadow: 0 15px 40px rgba(22, 163, 74, 0.4);
            }
            
            #pwa-install-button.show {
                transform: translateY(0);
            }
            
            @media (max-width: 768px) {
                #pwa-install-button {
                    bottom: 10px;
                    right: 10px;
                    left: 10px;
                    max-width: none;
                }
            }
        `;
        document.head.appendChild(style);

        // Add event listeners
        this.installButton.addEventListener('click', (e) => {
            if (e.target.closest('.pwa-install-close')) {
                this.hideInstallButton();
            } else {
                this.installApp();
            }
        });

        document.body.appendChild(this.installButton);
    }

    showInstallButton() {
        if (this.installButton) {
            setTimeout(() => {
                this.installButton.classList.add('show');
            }, 3000); // Show after 3 seconds
        }
    }

    hideInstallButton() {
        if (this.installButton) {
            this.installButton.classList.remove('show');
        }
    }

    async installApp() {
        if (!this.deferredPrompt) {
            return;
        }

        this.deferredPrompt.prompt();
        
        const { outcome } = await this.deferredPrompt.userChoice;
        
        if (outcome === 'accepted') {
            console.log('User accepted the install prompt');
        } else {
            console.log('User dismissed the install prompt');
        }
        
        this.deferredPrompt = null;
        this.hideInstallButton();
    }

    showInstalledMessage() {
        // Show success message
        const message = document.createElement('div');
        message.innerHTML = `
            <div class="pwa-installed-message">
                <i class="fas fa-check-circle"></i>
                <span>App successfully installed!</span>
            </div>
        `;
        
        message.style.cssText = `
            position: fixed;
            top: 20px;
            right: 20px;
            background: linear-gradient(135deg, #16a34a 0%, #15803d 100%);
            color: white;
            padding: 15px 20px;
            border-radius: 10px;
            box-shadow: 0 10px 30px rgba(22, 163, 74, 0.3);
            z-index: 10000;
            transform: translateX(100%);
            transition: transform 0.3s ease;
        `;
        
        const messageStyle = document.createElement('style');
        messageStyle.textContent = `
            .pwa-installed-message {
                display: flex;
                align-items: center;
                gap: 10px;
            }
        `;
        document.head.appendChild(messageStyle);
        
        document.body.appendChild(message);
        
        setTimeout(() => {
            message.style.transform = 'translateX(0)';
        }, 100);
        
        setTimeout(() => {
            message.style.transform = 'translateX(100%)';
            setTimeout(() => {
                document.body.removeChild(message);
                document.head.removeChild(messageStyle);
            }, 300);
        }, 3000);
    }
}

// Initialize PWA Installer when DOM is loaded
document.addEventListener('DOMContentLoaded', () => {
    new PWAInstaller();
});

// Service Worker Registration
if ('serviceWorker' in navigator) {
    window.addEventListener('load', () => {
        navigator.serviceWorker.register('/salem_sw.js')
            .then((registration) => {
                console.log('SW registered: ', registration);
            })
            .catch((registrationError) => {
                console.log('SW registration failed: ', registrationError);
            });
    });
}

// Request notification permission
if ('Notification' in navigator && 'Notification' in window) {
    Notification.requestPermission().then(function (result) {
        if (result === 'granted') {
            console.log('Notification permission granted.');
        }
    });
}

// Add to home screen prompt for iOS
function isIOS() {
    return /iPad|iPhone|iPod/.test(navigator.userAgent) && !window.MSStream;
}

function isStandalone() {
    return ('standalone' in window.navigator) && window.navigator.standalone;
}

function showIOSInstallPrompt() {
    if (isIOS() && !isStandalone()) {
        const prompt = document.createElement('div');
        prompt.innerHTML = `
            <div class="ios-install-prompt">
                <div class="ios-install-content">
                    <div class="ios-install-icon">
                        <i class="fas fa-mobile-alt"></i>
                    </div>
                    <div class="ios-install-text">
                        <div class="ios-install-title">Install Our App</div>
                        <div class="ios-install-desc">
                            Tap <i class="fas fa-share"></i> and then "Add to Home Screen"
                        </div>
                    </div>
                    <div class="ios-install-close">
                        <i class="fas fa-times"></i>
                    </div>
                </div>
            </div>
        `;
        
        prompt.style.cssText = `
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            background: linear-gradient(135deg, #16a34a 0%, #15803d 100%);
            color: white;
            padding: 15px;
            z-index: 9999;
            transform: translateY(-100%);
            transition: transform 0.3s ease;
        `;
        
        const iosStyle = document.createElement('style');
        iosStyle.textContent = `
            .ios-install-content {
                display: flex;
                align-items: center;
                gap: 15px;
                max-width: 400px;
                margin: 0 auto;
            }
            
            .ios-install-icon {
                font-size: 1.5rem;
            }
            
            .ios-install-text {
                flex: 1;
            }
            
            .ios-install-title {
                font-weight: 600;
                margin-bottom: 5px;
            }
            
            .ios-install-desc {
                font-size: 0.9rem;
                opacity: 0.9;
            }
            
            .ios-install-close {
                cursor: pointer;
                opacity: 0.7;
                transition: opacity 0.3s ease;
            }
            
            .ios-install-close:hover {
                opacity: 1;
            }
            
            .ios-install-prompt.show {
                transform: translateY(0);
            }
        `;
        document.head.appendChild(iosStyle);
        
        prompt.querySelector('.ios-install-close').addEventListener('click', () => {
            prompt.style.transform = 'translateY(-100%)';
            setTimeout(() => {
                document.body.removeChild(prompt);
                document.head.removeChild(iosStyle);
            }, 300);
        });
        
        document.body.appendChild(prompt);
        
        setTimeout(() => {
            prompt.classList.add('show');
        }, 5000);
        
        setTimeout(() => {
            prompt.classList.remove('show');
            setTimeout(() => {
                if (document.body.contains(prompt)) {
                    document.body.removeChild(prompt);
                    document.head.removeChild(iosStyle);
                }
            }, 300);
        }, 15000);
    }
}

// Show iOS install prompt after page load
window.addEventListener('load', () => {
    setTimeout(showIOSInstallPrompt, 3000);
});

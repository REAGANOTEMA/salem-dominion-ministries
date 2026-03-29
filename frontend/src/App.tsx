import { Toaster } from "@/components/ui/toaster";
import { Toaster as Sonner } from "@/components/ui/sonner";
import { TooltipProvider } from "@/components/ui/tooltip";
import { QueryClient, QueryClientProvider } from "@tanstack/react-query";
import { BrowserRouter, Routes, Route } from "react-router-dom";
import { useEffect } from "react";
import { AuthProvider } from "@/contexts/AuthContext";
import Index from "./pages/Index";
import About from "./pages/About";
import Leadership from "./pages/Leadership";
import Ministries from "./pages/Ministries";
import Sermons from "./pages/Sermons";
import Events from "./pages/Events";
import Donate from "./pages/Donate";
import Gallery from "./pages/Gallery";
import Blog from "./pages/Blog";
import Contact from "./pages/Contact";
import NotFound from "./pages/NotFound";
import ScrollToTop from "./components/ScrollToTop";
import SeoManager from "./components/SeoManager";
import FaviconManager from "./components/FaviconManager";
import { pwaManager } from "./utils/pwa";

const queryClient = new QueryClient();

const App = () => {
  useEffect(() => {
    // Initialize PWA functionality
    const initPWA = async () => {
      // Register service worker
      await pwaManager.registerServiceWorker();
      
      // Setup install prompt
      pwaManager.setupInstallPrompt();
      
      // Request notification permission
      await pwaManager.requestNotificationPermission();
    };

    initPWA();
  }, []);

  return (
    <AuthProvider>
      <QueryClientProvider client={queryClient}>
        <TooltipProvider>
          <Toaster />
          <Sonner />
          <BrowserRouter basename="/salem-dominion-ministries">
          <a
            href="#main-content"
            className="sr-only focus:not-sr-only focus:absolute focus:left-3 focus:top-3 focus:z-[100] focus:rounded-md focus:bg-primary focus:px-4 focus:py-2 focus:text-primary-foreground"
          >
            Skip to content
          </a>
          
          {/* PWA Install Button */}
          <button
            id="install-app-btn"
            className="fixed bottom-4 right-4 z-50 px-4 py-2 bg-gradient-gold text-primary font-semibold rounded-lg shadow-gold-enhanced hover:scale-105 transition hidden"
            style={{ display: 'none' }}
          >
            📱 Install App
          </button>
          
          {/* Connection Status Indicator */}
          <div
            id="connection-status"
            className="fixed top-4 right-4 z-50 px-3 py-1 text-xs font-semibold rounded-full bg-white/10 backdrop-blur-sm border border-white/20"
          >
            Online
          </div>
          
          <ScrollToTop />
          <SeoManager />
          <FaviconManager />
          <main id="main-content">
            <Routes>
              <Route path="/" element={<Index />} />
              <Route path="/about" element={<About />} />
              <Route path="/leadership" element={<Leadership />} />
              <Route path="/ministries" element={<Ministries />} />
              <Route path="/sermons" element={<Sermons />} />
              <Route path="/events" element={<Events />} />
              <Route path="/donate" element={<Donate />} />
              <Route path="/gallery" element={<Gallery />} />
              <Route path="/blog" element={<Blog />} />
              <Route path="/contact" element={<Contact />} />
              <Route path="*" element={<NotFound />} />
            </Routes>
          </main>
        </BrowserRouter>
      </TooltipProvider>
    </QueryClientProvider>
    </AuthProvider>
  );
};

export default App;

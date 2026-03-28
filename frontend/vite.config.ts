import { defineConfig } from "vite";
import react from "@vitejs/plugin-react";
import path from "path";

// https://vitejs.dev/config/
export default defineConfig(({ mode }) => ({
  // Set the base path for production to match your subfolder
  base: mode === "production" ? "/salem-dominion-ministries/" : "/",

  server: {
    host: "0.0.0.0", // Allow access from other devices
    port: 5173,
    hmr: {
      overlay: false,
    },
    // Proxy API calls if needed
    proxy: {
      "/api": {
        target: "http://localhost/salem-dominion-ministries/api",
        changeOrigin: true,
        secure: false,
      },
    },
  },

  plugins: [react()],

  resolve: {
    alias: {
      "@": path.resolve(__dirname, "./src"),
    },
  },

  build: {
    outDir: "dist",
    assetsDir: "assets",
    sourcemap: false,
    rollupOptions: {
      output: {
        manualChunks: {
          vendor: ["react", "react-dom"],
          ui: ["@radix-ui/react-dialog", "@radix-ui/react-dropdown-menu", "lucide-react"],
        },
      },
    },
  },
}));
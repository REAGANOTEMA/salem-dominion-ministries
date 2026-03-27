#!/usr/bin/env python3
import http.server
import socketserver
import os
import mimetypes
from urllib.parse import urlparse, unquote

class CustomHTTPRequestHandler(http.server.SimpleHTTPRequestHandler):
    def __init__(self, *args, **kwargs):
        super().__init__(*args, directory=os.path.dirname(os.path.abspath(__file__)), **kwargs)
    
    def end_headers(self):
        # Add CORS headers
        self.send_header('Access-Control-Allow-Origin', '*')
        self.send_header('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, OPTIONS')
        self.send_header('Access-Control-Allow-Headers', 'Content-Type, Authorization, X-Requested-With')
        self.send_header('Access-Control-Allow-Credentials', 'true')
        self.send_header('Cross-Origin-Resource-Policy', 'cross-origin')
        super().end_headers()
    
    def do_OPTIONS(self):
        self.send_response(200)
        self.end_headers()
    
    def guess_type(self, path):
        """Guess the type of a file."""
        mimetype, encoding = mimetypes.guess_type(path)
        if mimetype is None:
            return 'application/octet-stream'
        return mimetype
    
    def do_GET(self):
        """Handle GET requests."""
        parsed_path = urlparse(self.path)
        path = unquote(parsed_path.path)
        
        # API proxy
        if path.startswith('/api/'):
            self.proxy_api(path)
            return
        
        # Static file serving
        file_path = os.path.join(os.path.dirname(os.path.abspath(__file__)), path.lstrip('/'))
        
        if os.path.isfile(file_path):
            self.serve_file(file_path)
        else:
            # SPA fallback - serve index.html for all other routes
            self.serve_file(os.path.join(os.path.dirname(os.path.abspath(__file__)), 'index.html'))
    
    def serve_file(self, file_path):
        """Serve a file with proper MIME type."""
        try:
            with open(file_path, 'rb') as f:
                content = f.read()
            
            content_type = self.guess_type(file_path)
            self.send_response(200)
            self.send_header('Content-Type', content_type)
            self.send_header('Content-Length', str(len(content)))
            
            # Add caching for static assets
            if any(file_path.endswith(ext) for ext in ['.css', '.js', '.png', '.jpg', '.jpeg', '.svg', '.ico']):
                self.send_header('Cache-Control', 'public, max-age=31536000')
            
            self.end_headers()
            self.wfile.write(content)
        except FileNotFoundError:
            self.send_error(404, "File Not Found")
        except Exception as e:
            self.send_error(500, f"Internal Server Error: {str(e)}")
    
    def proxy_api(self, path):
        """Proxy API requests to backend."""
        import urllib.request
        import json
        
        try:
            backend_url = f"http://localhost/salem-dominion-ministries/api{path[4:]}"
            
            req = urllib.request.Request(backend_url)
            req.add_header('Content-Type', 'application/json')
            req.add_header('Access-Control-Allow-Origin', '*')
            
            if self.command == 'POST':
                content_length = int(self.headers.get('Content-Length', 0))
                post_data = self.rfile.read(content_length)
                req.data = post_data
            
            with urllib.request.urlopen(req) as response:
                response_data = response.read()
                self.send_response(200)
                self.send_header('Content-Type', 'application/json')
                self.send_header('Content-Length', str(len(response_data)))
                self.end_headers()
                self.wfile.write(response_data)
        except Exception as e:
            self.send_error(500, f"API Proxy Error: {str(e)}")

def run_server():
    """Run the server."""
    PORT = 8080
    HOST = '0.0.0.0'
    
    print("Salem Dominion Ministries - Python Server")
    print("=====================================")
    print(f"Starting server on http://{HOST}:{PORT}")
    print(f"Document Root: {os.path.dirname(os.path.abspath(__file__))}")
    print("Features:")
    print("  - Perfect MIME types")
    print("  - Full CORS support")
    print("  - API proxy to backend")
    print("  - SPA routing")
    print("  - Static file serving")
    print("=====================================")
    print("🌐 Access: http://localhost:8080")
    print("📱 Mobile: http://127.0.0.1:8080")
    print("⏹ Press Ctrl+C to stop")
    print()
    
    with socketserver.TCPServer((HOST, PORT), CustomHTTPRequestHandler) as httpd:
        try:
            httpd.serve_forever()
        except KeyboardInterrupt:
            print("\nServer stopped.")
            httpd.shutdown()

if __name__ == '__main__':
    run_server()

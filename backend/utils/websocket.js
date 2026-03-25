const WebSocket = require('ws');
const jwt = require('jsonwebtoken');

class WebSocketManager {
  constructor() {
    this.wss = null;
    this.clients = new Map(); // Map of client connections
    this.rooms = new Map(); // Map of rooms for broadcasting
  }

  // Initialize WebSocket server
  initialize(server) {
    this.wss = new WebSocket.Server({ 
      server,
      path: '/ws'
    });

    this.wss.on('connection', (ws, req) => {
      this.handleConnection(ws, req);
    });

    console.log('🔌 WebSocket server initialized');
  }

  // Handle new connection
  handleConnection(ws, req) {
    const clientId = this.generateClientId();
    
    console.log(`📱 New WebSocket connection: ${clientId}`);

    // Store client connection
    this.clients.set(clientId, {
      ws,
      user: null,
      rooms: new Set(),
      lastPing: Date.now()
    });

    // Handle messages from client
    ws.on('message', (message) => {
      this.handleMessage(clientId, message);
    });

    // Handle connection close
    ws.on('close', () => {
      this.handleDisconnection(clientId);
    });

    // Handle errors
    ws.on('error', (error) => {
      console.error(`WebSocket error for client ${clientId}:`, error);
    });

    // Send welcome message
    this.sendToClient(clientId, {
      type: 'connection',
      message: 'Connected to Salem Dominion Ministries real-time updates',
      clientId
    });

    // Start ping interval for connection health
    this.startPingInterval(clientId);
  }

  // Handle incoming messages
  async handleMessage(clientId, message) {
    try {
      const data = JSON.parse(message);
      const client = this.clients.get(clientId);

      if (!client) return;

      switch (data.type) {
        case 'authenticate':
          await this.authenticateClient(clientId, data.token);
          break;

        case 'join_room':
          this.joinRoom(clientId, data.room);
          break;

        case 'leave_room':
          this.leaveRoom(clientId, data.room);
          break;

        case 'ping':
          this.handlePing(clientId);
          break;

        default:
          console.log(`Unknown message type: ${data.type}`);
      }
    } catch (error) {
      console.error(`Error handling message from ${clientId}:`, error);
    }
  }

  // Authenticate client with JWT token
  async authenticateClient(clientId, token) {
    try {
      const decoded = jwt.verify(token, process.env.JWT_SECRET);
      const client = this.clients.get(clientId);

      if (client) {
        client.user = decoded;
        this.sendToClient(clientId, {
          type: 'authenticated',
          user: {
            id: decoded.userId,
            email: decoded.email
          }
        });
        console.log(`✅ Client ${clientId} authenticated as ${decoded.email}`);
      }
    } catch (error) {
      console.error(`Authentication failed for client ${clientId}:`, error);
      this.sendToClient(clientId, {
        type: 'error',
        message: 'Authentication failed'
      });
    }
  }

  // Join a room for receiving specific updates
  joinRoom(clientId, room) {
    const client = this.clients.get(clientId);
    if (!client) return;

    client.rooms.add(room);
    
    if (!this.rooms.has(room)) {
      this.rooms.set(room, new Set());
    }
    this.rooms.get(room).add(clientId);

    this.sendToClient(clientId, {
      type: 'joined_room',
      room
    });

    console.log(`📢 Client ${clientId} joined room: ${room}`);
  }

  // Leave a room
  leaveRoom(clientId, room) {
    const client = this.clients.get(clientId);
    if (!client) return;

    client.rooms.delete(room);
    
    const roomClients = this.rooms.get(room);
    if (roomClients) {
      roomClients.delete(clientId);
      if (roomClients.size === 0) {
        this.rooms.delete(room);
      }
    }

    this.sendToClient(clientId, {
      type: 'left_room',
      room
    });

    console.log(`📢 Client ${clientId} left room: ${room}`);
  }

  // Handle ping for connection health
  handlePing(clientId) {
    const client = this.clients.get(clientId);
    if (client) {
      client.lastPing = Date.now();
      this.sendToClient(clientId, { type: 'pong' });
    }
  }

  // Start ping interval
  startPingInterval(clientId) {
    const interval = setInterval(() => {
      const client = this.clients.get(clientId);
      if (!client) {
        clearInterval(interval);
        return;
      }

      // Check if client is still responsive
      if (Date.now() - client.lastPing > 30000) { // 30 seconds timeout
        console.log(`⚠️ Client ${clientId} timed out, disconnecting`);
        client.ws.terminate();
        this.handleDisconnection(clientId);
        clearInterval(interval);
      } else {
        // Send ping
        this.sendToClient(clientId, { type: 'ping' });
      }
    }, 15000); // Ping every 15 seconds
  }

  // Handle disconnection
  handleDisconnection(clientId) {
    const client = this.clients.get(clientId);
    if (!client) return;

    // Remove from all rooms
    client.rooms.forEach(room => {
      const roomClients = this.rooms.get(room);
      if (roomClients) {
        roomClients.delete(clientId);
        if (roomClients.size === 0) {
          this.rooms.delete(room);
        }
      }
    });

    // Remove client
    this.clients.delete(clientId);
    console.log(`📱 Client ${clientId} disconnected`);
  }

  // Send message to specific client
  sendToClient(clientId, data) {
    const client = this.clients.get(clientId);
    if (client && client.ws.readyState === WebSocket.OPEN) {
      client.ws.send(JSON.stringify(data));
    }
  }

  // Broadcast to all clients
  broadcast(data, excludeClientId = null) {
    this.clients.forEach((client, clientId) => {
      if (clientId !== excludeClientId && client.ws.readyState === WebSocket.OPEN) {
        client.ws.send(JSON.stringify(data));
      }
    });
  }

  // Broadcast to room
  broadcastToRoom(room, data, excludeClientId = null) {
    const roomClients = this.rooms.get(room);
    if (!roomClients) return;

    roomClients.forEach(clientId => {
      if (clientId !== excludeClientId) {
        this.sendToClient(clientId, data);
      }
    });
  }

  // Broadcast to authenticated users
  broadcastToAuthenticated(data, excludeClientId = null) {
    this.clients.forEach((client, clientId) => {
      if (clientId !== excludeClientId && client.user && client.ws.readyState === WebSocket.OPEN) {
        client.ws.send(JSON.stringify(data));
      }
    });
  }

  // Broadcast new content updates
  broadcastNewContent(contentType, data) {
    const message = {
      type: 'new_content',
      contentType,
      data,
      timestamp: new Date().toISOString()
    };

    // Send to all clients in general room
    this.broadcastToRoom('general', message);

    // Also send to specific content room
    this.broadcastToRoom(contentType, message);

    console.log(`📢 Broadcasted new ${contentType} content to ${this.clients.size} clients`);
  }

  // Broadcast breaking news
  broadcastBreakingNews(newsData) {
    const message = {
      type: 'breaking_news',
      data: newsData,
      timestamp: new Date().toISOString(),
      urgent: true
    };

    this.broadcast(message);
    console.log(`🚨 Broadcasted breaking news to ${this.clients.size} clients`);
  }

  // Broadcast prayer request update
  broadcastPrayerUpdate(prayerData, action = 'created') {
    const message = {
      type: 'prayer_update',
      action,
      data: prayerData,
      timestamp: new Date().toISOString()
    };

    this.broadcastToRoom('prayers', message);
    console.log(`🙏 Broadcasted prayer ${action} to prayer room`);
  }

  // Broadcast event update
  broadcastEventUpdate(eventData, action = 'created') {
    const message = {
      type: 'event_update',
      action,
      data: eventData,
      timestamp: new Date().toISOString()
    };

    this.broadcastToRoom('events', message);
    console.log(`📅 Broadcasted event ${action} to events room`);
  }

  // Broadcast donation update (admin only)
  broadcastDonationUpdate(donationData, action = 'received') {
    const message = {
      type: 'donation_update',
      action,
      data: donationData,
      timestamp: new Date().toISOString()
    };

    this.broadcastToAuthenticated(message);
    console.log(`💰 Broadcasted donation ${action} to authenticated users`);
  }

  // Get connection statistics
  getStats() {
    return {
      totalConnections: this.clients.size,
      authenticatedUsers: Array.from(this.clients.values()).filter(c => c.user).length,
      rooms: Array.from(this.rooms.entries()).map(([room, clients]) => ({
        room,
        clients: clients.size
      }))
    };
  }

  // Generate unique client ID
  generateClientId() {
    return Math.random().toString(36).substr(2, 9);
  }
}

// Export singleton instance
const wsManager = new WebSocketManager();

module.exports = wsManager;

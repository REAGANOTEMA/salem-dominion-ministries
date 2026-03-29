// API Configuration for Salem Dominion Ministries
// Centralized API endpoint management

export const API_BASE_URL = '/api'; // Relative path for production

// API Endpoints
export const API_ENDPOINTS = {
  // Authentication
  AUTH: {
    LOGIN: `${API_BASE_URL}/auth?action=login`,
    REGISTER: `${API_BASE_URL}/auth?action=register`,
    VERIFY: `${API_BASE_URL}/auth/verify`,
    UPLOAD_PROFILE: `${API_BASE_URL}/auth?action=upload_profile`,
  },
  
  // Users
  USERS: `${API_BASE_URL}/users`,
  
  // Events
  EVENTS: `${API_BASE_URL}/events`,
  
  // Sermons
  SERMONS: `${API_BASE_URL}/sermons`,
  
  // Prayers
  PRAYERS: `${API_BASE_URL}/prayers`,
  
  // Donations
  DONATIONS: `${API_BASE_URL}/donations`,
  
  // Contact
  CONTACT: `${API_BASE_URL}/contact`,
  
  // Blog
  BLOG: `${API_BASE_URL}/blog`,
  
  // Gallery
  GALLERY: `${API_BASE_URL}/gallery`,
  
  // News
  NEWS: `${API_BASE_URL}/news`,
  
  // Messages
  MESSAGES: `${API_BASE_URL}/messages`,
  
  // Notifications
  NOTIFICATIONS: `${API_BASE_URL}/notifications`,
  
  // Children's Ministry
  CHILDREN_MINISTRY: `${API_BASE_URL}/children_ministry`,
  
  // Health Check
  HEALTH: `${API_BASE_URL}/health`,
} as const;

// File Upload URLs
export const UPLOAD_URLS = {
  PROFILE: `${API_BASE_URL}/auth?action=upload_profile`,
  SERMON: `${API_BASE_URL}/sermons`,
  GALLERY: `${API_BASE_URL}/gallery`,
} as const;

// External Links
export const EXTERNAL_LINKS = {
  FACEBOOK: 'https://www.facebook.com/SalemDominionMinistries',
  YOUTUBE: 'https://youtube.com/@musasizifaty',
  INSTAGRAM: 'https://www.instagram.com/salemdominionministries',
  WHATSAPP: 'https://wa.me/256753244480',
  PHONE: 'tel:+256753244480',
  EMAIL: 'mailto:info@salemdominionministries.org',
  DESIGNER: 'https://www.ctiauganda.com',
  DEVELOPER_WHATSAPP: 'https://wa.me/256772514889',
} as const;

// Helper function to get full API URL
export const getApiUrl = (endpoint: string): string => {
  return endpoint;
};

// Helper function to get file URL
export const getFileUrl = (filename: string, type: 'profile' | 'gallery' | 'sermon' = 'gallery'): string => {
  const baseUrl = window.location.origin + '/salem-dominion-ministries/backend/uploads';
  switch (type) {
    case 'profile':
      return `${baseUrl}/profiles/${filename}`;
    case 'gallery':
      return `${baseUrl}/gallery/${filename}`;
    case 'sermon':
      return `${baseUrl}/sermons/${filename}`;
    default:
      return `${baseUrl}/${filename}`;
  }
};

// API Request helper
export const apiRequest = async (url: string, options: RequestInit = {}) => {
  const token = localStorage.getItem('token');
  const headers = {
    'Content-Type': 'application/json',
    ...(token && { Authorization: `Bearer ${token}` }),
    ...options.headers,
  };

  const response = await fetch(url, {
    ...options,
    headers,
  });

  if (!response.ok) {
    throw new Error(`API Error: ${response.status} ${response.statusText}`);
  }

  return response.json();
};

// File upload helper
export const uploadFile = async (url: string, file: File, additionalData: Record<string, string | number> = {}) => {
  const formData = new FormData();
  formData.append('file', file);
  
  Object.keys(additionalData).forEach(key => {
    formData.append(key, additionalData[key].toString());
  });

  const token = localStorage.getItem('token');
  const headers = {
    ...(token && { Authorization: `Bearer ${token}` }),
  };

  const response = await fetch(url, {
    method: 'POST',
    body: formData,
    headers,
  });

  if (!response.ok) {
    throw new Error(`Upload Error: ${response.status} ${response.statusText}`);
  }

  return response.json();
};

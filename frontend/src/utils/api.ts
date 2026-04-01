// API Configuration for Salem Dominion Ministries
// Supports both development and production environments

// Get API base URL from environment variable or use default
const API_BASE_URL = import.meta.env.VITE_API_URL || '/salem-dominion-ministries/api.php';

// API Endpoints
export const API_ENDPOINTS = {
  // Authentication
  AUTH: {
    LOGIN: `${API_BASE_URL}?route=auth&action=login`,
    REGISTER: `${API_BASE_URL}?route=auth&action=register`,
    VERIFY: `${API_BASE_URL}?route=auth/verify`,
    UPLOAD_PROFILE: `${API_BASE_URL}?route=auth&action=upload_profile`,
  },
  
  // Users
  USERS: `${API_BASE_URL}?route=users`,
  
  // Events
  EVENTS: `${API_BASE_URL}?route=events`,
  
  // Sermons
  SERMONS: `${API_BASE_URL}?route=sermons`,
  
  // Prayers
  PRAYERS: `${API_BASE_URL}?route=prayers`,
  
  // Prayer Booking
  PRAYER_BOOKING: `${API_BASE_URL}?route=prayer-booking`,
  
  // Donations
  DONATIONS: `${API_BASE_URL}?route=donations`,
  
  // Contact
  CONTACT: `${API_BASE_URL}?route=contact`,
  
  // Blog
  BLOG: `${API_BASE_URL}?route=blog`,
  
  // Gallery
  GALLERY: `${API_BASE_URL}?route=gallery`,
  
  // News
  NEWS: `${API_BASE_URL}?route=news`,
  
  // Breaking News
  BREAKING_NEWS: `${API_BASE_URL}?route=news&type=breaking`,
  
  // Messages
  MESSAGES: `${API_BASE_URL}?route=messages`,
  
  // Notifications
  NOTIFICATIONS: `${API_BASE_URL}?route=notifications`,
  
  // Children's Ministry
  CHILDREN_MINISTRY: `${API_BASE_URL}?route=children_ministry`,
  
  // Health Check
  HEALTH: `${API_BASE_URL}?route=health`,
} as const;

// File Upload URLs
export const UPLOAD_URLS = {
  PROFILE: `${API_BASE_URL}?route=auth&action=upload_profile`,
  SERMON: `${API_BASE_URL}?route=sermons`,
  GALLERY: `${API_BASE_URL}?route=gallery`,
} as const;

// External Links
export const EXTERNAL_LINKS = {
  FACEBOOK: 'https://www.facebook.com/SalemDominionMinistries',
  YOUTUBE: 'https://youtube.com/@musasizifaty',
  INSTAGRAM: 'https://www.instagram.com/salemdominionministries',
  WHATSAPP: 'https://wa.me/256753244480',
  PHONE: 'tel:+256753244480',
  EMAIL: 'mailto:visit@salemdominionministries.com',
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

// API Request helper with proper error handling
export const apiRequest = async <T>(url: string, options: RequestInit = {}): Promise<T> => {
  const token = localStorage.getItem('token');
  const headers: HeadersInit = {
    'Content-Type': 'application/json',
    ...(token && { Authorization: `Bearer ${token}` }),
    ...options.headers,
  };

  try {
    const response = await fetch(url, {
      ...options,
      headers,
    });

    // Check if response is JSON
    const contentType = response.headers.get('content-type');
    if (!contentType || !contentType.includes('application/json')) {
      const text = await response.text();
      console.error('Non-JSON response from:', url, 'Content:', text.substring(0, 200));
      throw new Error('Server returned non-JSON response. Please check API configuration.');
    }

    if (!response.ok) {
      const error = await response.json().catch(() => ({ message: 'Unknown error' }));
      throw new Error(`API Error: ${response.status} ${response.statusText} - ${error.message}`);
    }

    return response.json();
  } catch (error) {
    if (error instanceof TypeError && error.message.includes('fetch')) {
      throw new Error('Network error. Please check your connection.');
    }
    throw error;
  }
};

// File upload helper
export const uploadFile = async (url: string, file: File, additionalData: Record<string, string | number> = {}) => {
  const formData = new FormData();
  formData.append('file', file);
  
  Object.keys(additionalData).forEach(key => {
    formData.append(key, additionalData[key].toString());
  });

  const token = localStorage.getItem('token');
  const headers: HeadersInit = {
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
// API Configuration for Salem Dominion Ministries
// All API requests go to the backend server (localhost:5000 in dev, production URL in prod)

// Get API base URL from environment variable or use default
const API_BASE_URL = import.meta.env.VITE_API_URL || 'http://localhost:5000';

console.log('🔗 API Base URL:', API_BASE_URL);

// API Endpoints
export const API_ENDPOINTS = {
  // News
  NEWS: '/news',
  BREAKING_NEWS: '/news/breaking',
  
  // Gallery
  GALLERY: '/gallery',
  
  // Auth
  AUTH_LOGIN: '/auth/login',
  AUTH_REGISTER: '/auth/register',
  AUTH_VERIFY: '/auth/verify',
  
  // Other endpoints
  EVENTS: '/events',
  SERMONS: '/sermons',
  PRAYERS: '/prayers',
  PRAYER_BOOKING: '/prayer-booking',
  DONATIONS: '/donations',
  CONTACT: '/contact',
  BLOG: '/blog',
  MESSAGES: '/messages',
  NOTIFICATIONS: '/notifications',
  CHILDREN_MINISTRY: '/children-ministry',
  HEALTH: '/health',
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
  const headers: HeadersInit = {
    'Content-Type': 'application/json',
    ...(token && { Authorization: `Bearer ${token}` }),
    ...options.headers,
  };

  try {
    const res = await fetch(url, {
      ...options,
      headers,
    });

    console.log('📥 Response status:', res.status, res.statusText);

    // Get response as text first to debug
    const text = await res.text();
    
    console.log('📄 Response content (first 200 chars):', text.substring(0, 200));

    // Check if response is HTML (indicates wrong endpoint)
    if (text.trim().startsWith('<!DOCTYPE') || text.trim().startsWith('<html')) {
      console.error('❌ ERROR: Received HTML instead of JSON!');
      console.error('❌ This means the request hit the frontend instead of backend.');
      console.error('❌ Check that backend server is running at:', API_BASE_URL);
      throw new Error('API returned HTML instead of JSON. Check backend server.');
    }

    // Try to parse as JSON
    try {
      const data = JSON.parse(text);
      console.log('✅ Success:', endpoint, data);
      return data as T;
    } catch (parseError) {
      console.error('❌ Invalid JSON response:', text);
      console.error('❌ Parse error:', parseError);
      throw new Error('API did not return valid JSON');
    }
  } catch (error) {
    if (error instanceof TypeError && error.message.includes('fetch')) {
      console.error('❌ Network error. Is the backend server running at', API_BASE_URL, '?');
      throw new Error(`Cannot connect to backend server at ${API_BASE_URL}. Make sure it's running.`);
    }
    throw error;
  }
};

/**
 * POST request helper
 */
export const postAPI = async <T>(endpoint: string, data: unknown): Promise<T> => {
  return fetchAPI<T>(endpoint, {
    method: 'POST',
    body: JSON.stringify(data),
  });
};

/**
 * File upload helper
 */
export const uploadAPI = async <T>(endpoint: string, file: File, additionalData: Record<string, string | number> = {}): Promise<T> => {
  const url = `${API_BASE_URL}${endpoint}`;
  const formData = new FormData();
  formData.append('file', file);
  
  Object.keys(additionalData).forEach(key => {
    formData.append(key, additionalData[key].toString());
  });

  const token = localStorage.getItem('token');
  const headers: HeadersInit = {
    ...(token && { Authorization: `Bearer ${token}` }),
  };

  console.log('🔗 Uploading to:', url);

  const response = await fetch(url, {
    method: 'POST',
    body: formData,
    headers,
  });

  if (!response.ok) {
    throw new Error(`Upload failed: ${response.status}`);
  }

  return response.json();
};

/**
 * Get full URL for images/files
 */
export const getAssetUrl = (path: string): string => {
  if (path.startsWith('http')) return path;
  return `${API_BASE_URL}${path}`;
};
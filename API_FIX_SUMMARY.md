# API Fix Summary - Salem Dominion Ministries

## Problem
All API fetch calls were using `/api/` paths which return HTML (index.html) instead of JSON, causing "Unexpected token '<'" errors.

## Solution
All API calls must use `api.php?route=endpoint` format instead of `/api/endpoint`.

## Files Updated

### 1. `frontend/src/utils/api.ts`
- Created comprehensive API endpoint configuration
- Added `apiRequest()` helper with JSON validation
- All endpoints now use `api.php?route=endpoint` format

### 2. `frontend/src/components/NewsSection.tsx`
- Updated to use `API_ENDPOINTS` and `apiRequest()`
- Fixed `fetchNews()` and `fetchBreakingNews()` functions

### 3. `frontend/src/components/GallerySection.tsx`
- Updated to use `API_ENDPOINTS` and `apiRequest()`
- Fixed `fetchGallery()` and `fetchCategories()` functions

## Files Still Needing Updates

The following files still use old `/api/` paths and need to be updated:

### `frontend/src/contexts/AuthContext.tsx`
```typescript
// OLD:
fetch('/api/auth/verify')
fetch('/api/auth?action=login')
fetch('/api/auth?action=register')

// NEW:
apiRequest(API_ENDPOINTS.AUTH.VERIFY)
apiRequest(API_ENDPOINTS.AUTH.LOGIN, { method: 'POST', body: JSON.stringify(data) })
apiRequest(API_ENDPOINTS.AUTH.REGISTER, { method: 'POST', body: JSON.stringify(data) })
```

### `frontend/src/components/Auth/AuthModal.tsx`
```typescript
// OLD:
fetch(`/api/auth?action=${endpoint}`)

// NEW:
apiRequest(`${API_ENDPOINTS.AUTH.LOGIN}?action=${endpoint}`, ...)
```

### `frontend/src/components/Donation/DonationModal.tsx`
```typescript
// OLD:
fetch('/api/donations')

// NEW:
apiRequest(API_ENDPOINTS.DONATIONS, ...)
```

### `frontend/src/components/Pastor/PastorDashboard.tsx`
```typescript
// OLD:
fetch('/api/sermons')
fetch('/api/gallery')
fetch('/api/messages')
fetch('/api/notifications')

// NEW:
apiRequest(API_ENDPOINTS.SERMONS)
apiRequest(API_ENDPOINTS.GALLERY)
apiRequest(API_ENDPOINTS.MESSAGES)
apiRequest(API_ENDPOINTS.NOTIFICATIONS)
```

### `frontend/src/pages/PrayerRequest.tsx`
```typescript
// OLD:
fetch("/api/prayers")

// NEW:
apiRequest(API_ENDPOINTS.PRAYERS, ...)
```

### `frontend/src/pages/BookPrayerMeeting.tsx`
```typescript
// OLD:
fetch("/api/prayer-booking")

// NEW:
apiRequest(API_ENDPOINTS.PRAYER_BOOKING, ...)
```

## How to Use

1. Import the API utilities:
```typescript
import { API_ENDPOINTS, apiRequest } from '@/utils/api';
```

2. Replace fetch calls:
```typescript
// Before:
const response = await fetch('/api/news');
const data = await response.json();

// After:
const data = await apiRequest(API_ENDPOINTS.NEWS);
```

3. For POST requests:
```typescript
// Before:
const response = await fetch('/api/auth?action=login', {
  method: 'POST',
  body: JSON.stringify(data)
});
const result = await response.json();

// After:
const result = await apiRequest(API_ENDPOINTS.AUTH.LOGIN, {
  method: 'POST',
  body: JSON.stringify(data)
});
```

## Environment Variables

Create `.env` file in frontend directory:
```
VITE_API_URL=http://localhost/salem-dominion-ministries/api.php
```

For production:
```
VITE_API_URL=https://salemdominionministries.com/salem-dominion-ministries/api.php
```

## Debugging

The `apiRequest()` function now logs:
- 🔗 API Request URL and method
- 📥 Response status
- ✅ Success data
- ❌ Errors with full response content

Check browser console for these logs to debug API issues.
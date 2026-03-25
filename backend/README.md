# Salem Dominion Ministries Backend

A complete backend system for church management with MySQL database integration.

## Features

### 🔐 Authentication & Security
- JWT-based authentication
- Role-based access control (Admin, Pastor, Member, Visitor)
- Password hashing with bcrypt
- Rate limiting and security headers
- Input validation and sanitization

### 📅 Event Management
- Create, read, update, delete events
- Event registration system
- Recurring events support
- Category-based filtering
- Attendance tracking

### 📚 Sermon Management
- Sermon upload and management
- Audio/video support
- Bible reference tracking
- Sermon series organization
- View statistics

### 🙏 Prayer Request System
- Submit prayer requests
- Prayer response system
- Status tracking (pending, praying, answered)
- Anonymous prayer support
- Prayer categorization

### 💰 Donation Management
- Online donation processing
- Multiple donation types
- Recurring donations
- Transaction tracking
- Donation statistics and reporting

### 📧 Contact Management
- Contact form submissions
- Message categorization
- Priority system
- Response tracking
- Assignment system

### 👥 User Management
- User registration and profiles
- Role management
- Account activation/deactivation
- Password change functionality

### 📝 Blog System
- Blog post creation and management
- Category and tag system
- SEO optimization
- Draft/published status
- Featured posts

### 🖼️ Gallery Management
- Photo and video uploads
- Category organization
- Featured items
- File metadata tracking

## Database Schema

Complete MySQL database with 13 tables:
- `users` - User accounts and authentication
- `events` - Church events and registrations
- `sermons` - Sermon content and media
- `prayer_requests` - Prayer requests and responses
- `donations` - Financial donations and tracking
- `contact_messages` - Contact form submissions
- `blog_posts` - Blog content management
- `gallery` - Media gallery items
- `service_times` - Worship service schedules
- `ministries` - Church ministry information
- `testimonials` - Member testimonials
- `activity_logs` - Admin activity tracking

## API Endpoints

### Authentication
- `POST /api/auth/register` - User registration
- `POST /api/auth/login` - User login
- `GET /api/auth/verify` - Token verification
- `POST /api/auth/refresh` - Token refresh

### Events
- `GET /api/events` - Get all events
- `GET /api/events/:id` - Get single event
- `POST /api/events` - Create event (admin/pastor)
- `PUT /api/events/:id` - Update event (admin/pastor)
- `DELETE /api/events/:id` - Delete event (admin)
- `POST /api/events/:id/register` - Register for event

### Sermons
- `GET /api/sermons` - Get all sermons
- `GET /api/sermons/featured` - Get featured sermons
- `GET /api/sermons/:id` - Get single sermon
- `POST /api/sermons` - Create sermon (admin/pastor)
- `PUT /api/sermons/:id` - Update sermon (admin/pastor)
- `DELETE /api/sermons/:id` - Delete sermon (admin)

### Prayer Requests
- `GET /api/prayers` - Get public prayer requests
- `GET /api/prayers/admin/all` - Get all requests (admin/pastor)
- `POST /api/prayers` - Create prayer request
- `POST /api/prayers/:id/respond` - Add prayer response
- `PUT /api/prayers/:id/status` - Update status (admin/pastor)

### Donations
- `GET /api/donations` - Get donations (admin/pastor)
- `GET /api/donations/stats` - Get donation statistics
- `POST /api/donations` - Process donation
- `PUT /api/donations/:id/status` - Update status (admin/pastor)

### Contact Messages
- `GET /api/contact` - Get messages (admin/pastor)
- `GET /api/contact/:id` - Get single message (admin/pastor)
- `POST /api/contact` - Send contact message
- `PUT /api/contact/:id/status` - Update status (admin/pastor)

### Users
- `GET /api/users/profile` - Get user profile
- `PUT /api/users/profile` - Update profile
- `PUT /api/users/password` - Change password
- `GET /api/users` - Get all users (admin)
- `PUT /api/users/:id/role` - Update role (admin)

### Blog
- `GET /api/blog` - Get blog posts
- `GET /api/blog/featured` - Get featured posts
- `GET /api/blog/:slug` - Get single post
- `POST /api/blog` - Create post (admin/pastor)
- `PUT /api/blog/:id` - Update post (admin/pastor)
- `DELETE /api/blog/:id` - Delete post (admin)

### Gallery
- `GET /api/gallery` - Get gallery items
- `GET /api/gallery/featured` - Get featured items
- `GET /api/gallery/:id` - Get single item
- `POST /api/gallery` - Upload item (admin/pastor)
- `PUT /api/gallery/:id` - Update item (admin/pastor)
- `DELETE /api/gallery/:id` - Delete item (admin)

## Setup Instructions

### 1. Database Setup
```sql
-- Create database and import schema
mysql -u root -p < database/schema.sql
```

### 2. Environment Configuration
Copy `.env.example` to `.env` and update:
```env
DB_HOST=localhost
DB_USER=root
DB_PASSWORD=your_password
DB_NAME=salem_dominion_ministries
JWT_SECRET=your_super_secret_jwt_key
```

### 3. Install Dependencies
```bash
npm install
```

### 4. Start Server
```bash
npm run dev  # Development mode
npm start    # Production mode
```

## Admin Dashboard

Access the admin dashboard at:
- Login: `http://localhost:5000/login.html`
- Dashboard: `http://localhost:5000/admin-dashboard.html`

Default admin credentials:
- Email: `admin@salemministries.org`
- Password: `admin123`

## Security Features

- **Rate Limiting**: 100 requests per 15 minutes per IP
- **CORS Protection**: Configured for frontend domain
- **Input Validation**: All inputs validated and sanitized
- **SQL Injection Protection**: Parameterized queries
- **XSS Protection**: Input sanitization and headers
- **Password Security**: bcrypt hashing with salt rounds

## Testing

### Health Check
```bash
curl http://localhost:5000/api/health
```

### API Testing
```bash
# Test authentication
curl -X POST http://localhost:5000/api/auth/login \
  -H "Content-Type: application/json" \
  -d '{"email":"admin@salemministries.org","password":"admin123"}'
```

## Deployment

### Production Setup
1. Set `NODE_ENV=production`
2. Use strong JWT secrets
3. Configure proper CORS origins
4. Set up SSL certificates
5. Configure reverse proxy (nginx/apache)
6. Set up database backups
7. Monitor logs and performance

## API Response Format

### Success Response
```json
{
  "success": true,
  "message": "Operation completed successfully",
  "data": { ... }
}
```

### Error Response
```json
{
  "success": false,
  "message": "Error description",
  "errors": [ ... ]
}
```

## File Structure
```
backend/
├── config/
│   └── database.js     # Database configuration
├── middleware/
│   ├── auth.js         # Authentication middleware
│   └── validation.js   # Input validation
├── routes/
│   ├── auth.js         # Authentication routes
│   ├── events.js       # Event management
│   ├── sermons.js      # Sermon management
│   ├── prayers.js      # Prayer requests
│   ├── donations.js    # Donation processing
│   ├── contact.js      # Contact messages
│   ├── users.js        # User management
│   ├── blog.js         # Blog management
│   └── gallery.js      # Gallery management
├── database/
│   └── schema.sql      # Database schema
├── uploads/            # File upload directory
├── admin-dashboard.html # Admin interface
├── login.html         # Admin login
├── server.js          # Main server file
├── package.json       # Dependencies
└── .env              # Environment variables
```

## Support

For technical support or questions:
- Check the console logs for detailed error messages
- Verify database connection and credentials
- Ensure all environment variables are set
- Check API endpoint permissions and roles

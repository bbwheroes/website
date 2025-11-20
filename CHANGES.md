# Changes from Old Website

## Technology Stack Migration

### From (Next.js)
- Next.js 14
- React
- PostgreSQL
- NextAuth
- Nextra (for wiki)

### To (Laravel)
- Laravel 12
- Blade Templates
- SQLite (configurable to Turso/PostgreSQL)
- FilamentPHP 4
- Alpine.js

## Features Removed

✅ **Wiki Section** - Removed as requested
✅ **Services Section** - Removed as requested
✅ **Direct Accept/Decline URLs** - Changed to admin panel only

## Features Added

✅ **FilamentPHP Admin Panel** (`/admin`)
- User management with admin role assignment
- Contribution request management
- Accept/decline requests with notifications
- Admin notes for requests

✅ **Enhanced Discord Integration**
- Notifications link to admin panel instead of direct URLs
- Better formatted embeds with colors
- Notifications for accepted/declined requests

✅ **Improved GitHub Integration**
- 1-hour caching for repository data
- Cache hint displayed on projects page
- Automatic filtering of 4-part format repos
- Better error handling

## Features Preserved

✅ **4-Input Search Bar** - Kept the cool search interface
- Module (3 digits)
- Teacher (4 letters)
- Task name (full text)
- Username (GitHub username)

✅ **BBW Brand Colors** - Same color scheme
- Primary: `#b3d800`
- Full palette from 50-900

✅ **Logo** - Same BBW Heroes logo

✅ **Contribution Form** - Enhanced with better validation
- Module number validation
- Teacher name validation
- Auto-slugified task name
- Collaborators management
- Real-time form validation

## Design Improvements

✅ **Modern UI**
- Cleaner, more spacious layout
- Better responsive design
- Improved typography
- Smooth transitions and hover effects

✅ **Footer Enhancement**
- Admin panel link
- Disclaimer text as requested
- Copyright information

## Database Schema Changes

### New Tables
1. **users** - Enhanced with `is_admin` field
2. **projects** - Simplified, linked to users
3. **contribution_requests** - New table for managing requests
   - Separate from projects
   - Status tracking (pending/accepted/declined)
   - Admin notes support

### Removed Tables
- `proposals` - Replaced with `contribution_requests`

## Configuration

### New Environment Variables
```env
GITHUB_TOKEN=              # GitHub API token
GITHUB_ORGANIZATION=       # Default: bbwheroes
DISCORD_WEBHOOK_URL=       # Discord webhook for notifications
```

## Admin Panel Features

### Users Resource
- Create/edit/delete users
- Assign admin roles
- View user projects
- Search and filter

### Contribution Requests Resource
- View all requests
- Filter by status
- Accept/decline with one click
- Add admin notes
- Automatic project creation on acceptance
- Discord notifications

## Security Improvements

✅ **Admin Middleware** - Only admin users can access admin panel
✅ **CSRF Protection** - Built-in Laravel protection
✅ **Password Hashing** - Bcrypt hashing
✅ **Form Validation** - Server-side validation

## Performance Optimizations

✅ **GitHub API Caching** - 1-hour cache reduces API calls
✅ **Database Indexing** - Proper indexes on search fields
✅ **Asset Optimization** - Vite for fast builds
✅ **Lazy Loading** - Alpine.js for minimal JS footprint

## Developer Experience

✅ **Better Documentation** - README and SETUP guides
✅ **Composer Scripts** - `composer dev` for easy development
✅ **Seeder** - Quick admin user creation
✅ **Modern Stack** - Latest Laravel, Tailwind, and Filament

## Migration Path

To migrate from old to new:

1. Export existing data from PostgreSQL
2. Import into new SQLite/database
3. Update environment variables
4. Run migrations
5. Create admin users
6. Configure GitHub/Discord integrations

## Future Enhancements

Potential additions:
- [ ] Turso database integration
- [ ] Email notifications
- [ ] User authentication for contributors
- [ ] Project statistics dashboard
- [ ] API endpoints for external integrations
- [ ] Advanced search filters
- [ ] Project tags/categories

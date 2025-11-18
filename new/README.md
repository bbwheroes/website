# BBW Heroes Website

Where BBW students come together to form a community.

## About

This is a modern Laravel application built with:
- **Laravel 12** - PHP framework
- **FilamentPHP 4** - Admin panel
- **Tailwind CSS 4** - Styling
- **Alpine.js** - Interactive components
- **SQLite** - Database (can be switched to Turso)

## Features

- 🎨 Modern, clean design with BBW brand colors
- 🔍 4-part search bar for filtering projects (module-teacher-taskname-username)
- 📝 Contribution request form with Discord notifications
- 👨‍💼 Admin panel for managing users and contribution requests
- 🔄 GitHub repository integration with 1-hour caching
- 📱 Responsive design

## Requirements

- PHP 8.2 or higher
- Composer
- Node.js & npm
- SQLite (or PostgreSQL/MySQL for production)

## Installation

1. **Clone the repository**
   ```bash
   git clone <repository-url>
   cd website/new
   ```

2. **Install dependencies**
   ```bash
   composer install
   npm install
   ```

3. **Set up environment**
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

4. **Configure environment variables**
   Edit `.env` and set:
   ```env
   APP_NAME="BBW Heroes"
   APP_URL=http://localhost:8000
   
   # GitHub API (optional, for fetching repositories)
   GITHUB_TOKEN=your_github_personal_access_token
   GITHUB_ORGANIZATION=bbwheroes
   
   # Discord Webhook (optional, for notifications)
   DISCORD_WEBHOOK_URL=your_discord_webhook_url
   ```

5. **Create database and run migrations**
   ```bash
   touch database/database.sqlite
   php artisan migrate
   ```

6. **Create admin user**
   ```bash
   php artisan db:seed --class=AdminUserSeeder
   ```
   Default credentials: `admin@bbwheroes.ch` / `password`

7. **Build assets**
   ```bash
   npm run build
   ```

## Development

Run the development server:

```bash
composer dev
```

This will start:
- Laravel development server on http://localhost:8000
- Vite dev server for hot module replacement
- Queue worker for background jobs

Or run them separately:
```bash
php artisan serve
npm run dev
```

## Admin Panel

Access the admin panel at `/admin` with admin credentials.

**Features:**
- Manage users and assign admin roles
- Review contribution requests
- Accept or decline requests
- Send Discord notifications

## Project Structure

```
app/
├── Filament/Resources/     # Admin panel resources
├── Http/Controllers/       # Web controllers
├── Models/                 # Eloquent models
└── Services/              # Business logic (GitHub API)

resources/
├── css/                   # Tailwind CSS
├── js/                    # Alpine.js components
└── views/                 # Blade templates

database/
└── migrations/            # Database schema
```

## GitHub Integration

The application fetches repositories from the `bbwheroes` GitHub organization. Only repositories matching the 4-part naming format are displayed:

Format: `{module}-{teacher}-{taskname}-{username}`

Example: `431-ober-linux_cookbook-lorenzhohermuth`

Repositories are cached for 1 hour to reduce API calls.

## Discord Integration

When a contribution request is submitted or processed, a notification is sent to Discord (if webhook URL is configured).

## Customization

### Colors

BBW brand colors are defined in `resources/css/app.css`:
- Primary: `#b3d800` (bbw-400)
- Variations: bbw-50 through bbw-900

### Logo

Replace `public/bbwheroes.svg` with your custom logo.

## Deployment

For production deployment:

1. Set `APP_ENV=production` and `APP_DEBUG=false`
2. Use a production database (PostgreSQL, MySQL, or Turso)
3. Configure proper cache and session drivers
4. Set up queue workers
5. Build production assets: `npm run build`

## License

MIT License

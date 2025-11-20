# Quick Setup Guide

## First Time Setup

Run these commands in order:

```bash
# 1. Install PHP dependencies
composer install

# 2. Install Node dependencies
npm install

# 3. Copy environment file
cp .env.example .env

# 4. Generate application key
php artisan key:generate

# 5. Create SQLite database file
touch database/database.sqlite

# 6. Run migrations
php artisan migrate

# 7. Create admin user
php artisan db:seed --class=AdminUserSeeder

# 8. Build frontend assets
npm run build
```

## Running the Application

### Option 1: All-in-one (Recommended)
```bash
composer dev
```

This starts Laravel server, Vite, and queue worker simultaneously.

### Option 2: Separate terminals
```bash
# Terminal 1: Laravel server
php artisan serve

# Terminal 2: Vite dev server
npm run dev

# Terminal 3 (optional): Queue worker
php artisan queue:listen
```

## Access Points

- **Website**: http://localhost:8000
- **Admin Panel**: http://localhost:8000/admin
  - Email: `admin@bbwheroes.ch`
  - Password: `password`

## Configuration

### GitHub API (Optional)
To fetch real repositories from GitHub:

1. Create a GitHub Personal Access Token at https://github.com/settings/tokens
2. Add to `.env`:
   ```env
   GITHUB_TOKEN=your_token_here
   GITHUB_ORGANIZATION=bbwheroes
   ```

### Discord Webhook (Optional)
To receive notifications in Discord:

1. Create a webhook in your Discord server
2. Add to `.env`:
   ```env
   DISCORD_WEBHOOK_URL=https://discord.com/api/webhooks/...
   ```

## Troubleshooting

### "Class not found" errors
```bash
composer dump-autoload
```

### Database issues
```bash
php artisan migrate:fresh
php artisan db:seed --class=AdminUserSeeder
```

### Asset issues
```bash
npm run build
php artisan optimize:clear
```

### Cache issues
```bash
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear
```

## Next Steps

1. Change the default admin password in the admin panel
2. Configure GitHub token for repository fetching
3. Set up Discord webhook for notifications
4. Customize the design and content as needed

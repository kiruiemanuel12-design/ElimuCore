# Railway Environment Configuration

> Production environment variables for Railway.app deployment

## Backend Environment Variables

Copy these to Railway dashboard → backend service → Variables tab:

```env
# App Configuration
APP_NAME=ElimuCore
APP_ENV=production
APP_DEBUG=false
APP_URL=https://your-app.railway.app
APP_TIMEZONE=UTC

# Security
APP_KEY=base64:YOUR_GENERATED_KEY_HERE
SANCTUM_STATEFUL_DOMAINS=your-app.railway.app
CORS_ALLOWED_ORIGINS=https://your-app.railway.app,https://www.your-app.railway.app

# Database
DB_CONNECTION=mysql
DB_HOST=db
DB_PORT=3306
DB_DATABASE=elimucore
DB_USERNAME=elimucore_user
DB_PASSWORD=SECURE_PASSWORD_MIN_16_CHARS

# Cache & Session
CACHE_DRIVER=database
CACHE_PREFIX=elimucore_
SESSION_DRIVER=database
SESSION_DOMAIN=your-app.railway.app
SESSION_SECURE_COOKIES=true

# Queue & Jobs
QUEUE_CONNECTION=database
BROADCAST_DRIVER=log

# Mail (Optional - add if using email features)
MAIL_MAILER=smtp
MAIL_HOST=smtp.mailtrap.io
MAIL_PORT=465
MAIL_USERNAME=your_username
MAIL_PASSWORD=your_password
MAIL_FROM_ADDRESS=noreply@elimucore.local
MAIL_FROM_NAME=ElimuCore

# Laravel Logs
LOG_CHANNEL=single
LOG_LEVEL=info

# API Rate Limiting
API_RATE_LIMIT=60
```

## Frontend Environment Variables

Copy these to Railway dashboard → frontend service → Variables tab:

```env
# API Configuration
VITE_API_BASE_URL=https://your-app.railway.app/api
VITE_APP_NAME=ElimuCore
VITE_APP_DEBUG=false
```

## Database (MySQL) Environment Variables

Copy these to Railway dashboard → db service → Variables tab:

```env
# MySQL Configuration
MYSQL_DATABASE=elimucore
MYSQL_USER=elimucore_user
MYSQL_PASSWORD=SECURE_PASSWORD_MIN_16_CHARS
MYSQL_ROOT_PASSWORD=ROOT_SECURE_PASSWORD_MIN_16_CHARS
MYSQL_ALLOW_EMPTY_PASSWORD=false
```

## Nginx Configuration

The docker-compose.yml includes nginx configuration. For Railway, ensure CORS headers:

```nginx
add_header 'Access-Control-Allow-Origin' '$http_origin' always;
add_header 'Access-Control-Allow-Methods' 'GET, POST, PUT, DELETE, OPTIONS' always;
add_header 'Access-Control-Allow-Headers' 'Content-Type, Authorization' always;
add_header 'Access-Control-Allow-Credentials' 'true' always;
```

## Secure Password Generation

Generate secure passwords for Railway:

```bash
# Option 1: Using OpenSSL
openssl rand -base64 32

# Option 2: Using Python
python3 -c "import secrets; print(secrets.token_urlsafe(32))"

# Option 3: Using PHP
php -r "echo bin2hex(random_bytes(16));"
```

## Initial Deployment Checklist

- [ ] APP_KEY generated and set
- [ ] DB_PASSWORD and MYSQL_PASSWORD match
- [ ] MYSQL_ROOT_PASSWORD is strong
- [ ] DB_HOST is set to "db"
- [ ] APP_DEBUG set to false
- [ ] CORS_ALLOWED_ORIGINS configured
- [ ] SESSION_SECURE_COOKIES set to true
- [ ] VITE_API_BASE_URL points to correct domain

## Post-Deployment

After Railway deployment completes:

```bash
# Run migrations (via Railway CLI)
railway run php artisan migrate --force

# Seed sample data (optional)
railway run php artisan db:seed --class=DatabaseSeeder

# Create additional users
railway run php artisan tinker
```

## Monitoring Environment Variables

In Railway dashboard:

1. Click service (backend)
2. Go to "Variables" tab
3. Review all environment settings
4. Click "Deploy" if changes made
5. Check "Deploy Logs" for confirmation

## Security Notes

🔐 **IMPORTANT:**
- Never commit actual passwords to git
- Use Railway's "Shared Variables" for common values
- Rotate passwords periodically
- Use strong, random passwords (min 16 characters)
- Store passwords in password manager
- Don't share deployment URLs with untrusted users

## Domain Configuration

When you set custom domain in Railway:

```env
# Update these with your domain
APP_URL=https://yourdomain.com
SANCTUM_STATEFUL_DOMAINS=yourdomain.com
CORS_ALLOWED_ORIGINS=https://yourdomain.com,https://www.yourdomain.com
SESSION_DOMAIN=yourdomain.com
VITE_API_BASE_URL=https://yourdomain.com/api
```

Then redeploy both backend and frontend services.

---

*For detailed deployment instructions, see RAILWAY_DEPLOYMENT.md*

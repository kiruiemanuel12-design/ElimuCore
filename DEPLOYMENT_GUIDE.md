# ElimuCore SMIS - Complete Deployment Guide

## ⚠️ NOTE: AWS Deployment Recommended

**For production deployment, use [AWS_DEPLOYMENT_GUIDE.md](AWS_DEPLOYMENT_GUIDE.md)** 
- Free for 12 months on AWS Free Tier
- Complete setup with EC2 + RDS
- Production-ready infrastructure

**This guide is for:**
- Manual VPS/Server deployment
- Traditional on-premise installations
- Development environment setup

---

## Project Structure

```
ElimuCore/
├── backend/                    # Laravel API Server
│   ├── app/
│   ├── bootstrap/
│   ├── config/
│   ├── database/
│   ├── public/
│   ├── routes/
│   ├── storage/
│   ├── vendor/
│   ├── artisan
│   ├── composer.json
│   ├── composer.lock
│   ├── .env.example
│   └── .env (create from .env.example)
│
├── frontend/                   # Vue 3 + Vite App
│   ├── src/
│   ├── public/
│   ├── package.json
│   ├── vite.config.js
│   ├── .env.example
│   └── .env (create from .env.example)
│
├── docker-compose.yml          # Docker orchestration
├── Dockerfile.backend          # Backend container
├── Dockerfile.frontend         # Frontend container
│
├── DEPLOYMENT_GUIDE.md         # This file
├── API_DOCUMENTATION.md
├── QUICK_START.md
└── README.md
```

---

## Quick Start (Development)

### Backend Setup

```bash
cd backend

# Install dependencies
composer install

# Copy environment file
cp .env.example .env

# Generate app key
php artisan key:generate

# Run migrations
php artisan migrate

# Seed database
php artisan db:seed

# Start server
php artisan serve
# Server runs on http://localhost:8000
```

**Default Credentials:**
- Email: admin@elimucore.local
- Password: Admin@123

### Frontend Setup

```bash
cd frontend

# Install dependencies
npm install

# Create environment file
cp .env.example .env

# Update VITE_API_BASE_URL in .env to http://localhost:8000/api

# Start dev server
npm run dev
# Frontend runs on http://localhost:5173
```

---

## Docker Deployment (Recommended for Production)

### Prerequisites
- Docker
- Docker Compose

### One-Command Setup

```bash
# From root directory
docker-compose up -d

# View logs
docker-compose logs -f

# Stop services
docker-compose down
```

### Access
- **Frontend:** http://localhost
- **Backend API:** http://localhost/api
- **Database:** mysql://root:root@db:3306/elimucore

---

## Manual Deployment (VPS/Server)

### System Requirements

#### Backend (Laravel API)
- PHP 8.3+
- Composer
- MySQL 8.0+ or PostgreSQL 12+
- Redis (recommended)
- Nginx or Apache

#### Frontend (Vue 3)
- Node.js 18+
- npm or yarn
- Nginx or Apache

### Step 1: Backend Deployment

#### Install Dependencies

```bash
cd /var/www/elimucore/backend

# Install PHP dependencies
composer install --optimize-autoloader --no-dev

# Create environment file
cp .env.example .env

# Generate application key
php artisan key:generate

# Update .env with production settings
# APP_ENV=production
# APP_DEBUG=false
# DB_CONNECTION=mysql
# DB_HOST=your-db-host
# DB_DATABASE=elimucore
# DB_USERNAME=elimucore_user
# DB_PASSWORD=strong_password
```

#### Setup Database

```bash
# Create database
mysql -u root -p -e "CREATE DATABASE elimucore CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"

# Create user
mysql -u root -p -e "CREATE USER 'elimucore_user'@'localhost' IDENTIFIED BY 'strong_password';"
mysql -u root -p -e "GRANT ALL PRIVILEGES ON elimucore.* TO 'elimucore_user'@'localhost';"
mysql -u root -p -e "FLUSH PRIVILEGES;"

# Run migrations
php artisan migrate --force

# Seed initial data
php artisan db:seed --force
```

#### Configure Web Server (Nginx)

**Create `/etc/nginx/sites-available/elimucore-api`:**

```nginx
server {
    listen 80;
    server_name api.elimucore.local;

    root /var/www/elimucore/backend/public;
    index index.php;

    add_header X-Frame-Options "SAMEORIGIN" always;
    add_header X-Content-Type-Options "nosniff" always;
    add_header X-XSS-Protection "1; mode=block" always;

    location ~ /\.(?!well-known).* {
        deny all;
    }

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php8.3-fpm.sock;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
    }

    # Gzip compression
    gzip on;
    gzip_types text/plain text/css text/xml text/javascript application/x-javascript application/json;
}
```

**Enable site:**
```bash
sudo ln -s /etc/nginx/sites-available/elimucore-api /etc/nginx/sites-enabled/
sudo nginx -t
sudo systemctl restart nginx
```

#### Setup PHP-FPM

```bash
# Check PHP-FPM status
sudo systemctl status php8.3-fpm

# Restart if needed
sudo systemctl restart php8.3-fpm
```

#### Optimize Laravel

```bash
# Cache configuration
php artisan config:cache

# Cache routes
php artisan route:cache

# Cache views
php artisan view:cache

# Optimize autoloader
composer dump-autoload --optimize
```

#### Setup Cron Job (for scheduled tasks)

```bash
# Edit crontab
crontab -e

# Add this line:
* * * * * cd /var/www/elimucore/backend && php artisan schedule:run >> /dev/null 2>&1
```

---

### Step 2: Frontend Deployment

#### Build for Production

```bash
cd /var/www/elimucore/frontend

# Install dependencies
npm install

# Create environment file
cp .env.example .env

# Update VITE_API_BASE_URL in .env
# VITE_API_BASE_URL=https://api.elimucore.local/api

# Build for production
npm run build

# Output in dist/ directory
```

#### Configure Web Server (Nginx)

**Create `/etc/nginx/sites-available/elimucore-web`:**

```nginx
server {
    listen 80;
    server_name elimucore.local;

    root /var/www/elimucore/frontend/dist;
    index index.html;

    add_header X-Frame-Options "SAMEORIGIN" always;
    add_header X-Content-Type-Options "nosniff" always;
    add_header X-XSS-Protection "1; mode=block" always;

    # Gzip compression
    gzip on;
    gzip_types text/plain text/css text/xml text/javascript application/x-javascript application/json;

    location / {
        try_files $uri $uri/ /index.html;
    }

    # Cache static assets
    location ~* \.(js|css|png|jpg|jpeg|gif|ico|svg|woff|woff2|ttf|eot)$ {
        expires 1y;
        add_header Cache-Control "public, immutable";
    }
}
```

**Enable site:**
```bash
sudo ln -s /etc/nginx/sites-available/elimucore-web /etc/nginx/sites-enabled/
sudo nginx -t
sudo systemctl restart nginx
```

---

## HTTPS/SSL Configuration

### Using Let's Encrypt (Recommended)

```bash
# Install Certbot
sudo apt-get install certbot python3-certbot-nginx

# Get certificates
sudo certbot certonly --nginx -d api.elimucore.local
sudo certbot certonly --nginx -d elimucore.local

# Auto-renewal
sudo systemctl enable certbot.timer
sudo systemctl start certbot.timer
```

### Update Nginx for HTTPS

**Add to both nginx configs:**

```nginx
# Redirect HTTP to HTTPS
server {
    listen 80;
    server_name elimucore.local api.elimucore.local;
    return 301 https://$server_name$request_uri;
}

server {
    listen 443 ssl http2;
    ssl_certificate /etc/letsencrypt/live/domain.com/fullchain.pem;
    ssl_certificate_key /etc/letsencrypt/live/domain.com/privkey.pem;
    
    # ... rest of config
}
```

---

## Environment Configuration

### Backend (.env)

```env
APP_NAME=ElimuCore
APP_ENV=production
APP_KEY=base64:xxxxx
APP_DEBUG=false
APP_URL=https://api.elimucore.local

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=elimucore
DB_USERNAME=elimucore_user
DB_PASSWORD=strong_password

CACHE_DRIVER=redis
QUEUE_CONNECTION=redis
SESSION_DRIVER=redis

REDIS_HOST=127.0.0.1
REDIS_PASSWORD=null
REDIS_PORT=6379

MAIL_MAILER=smtp
MAIL_HOST=smtp.mailtrap.io
MAIL_PORT=465
MAIL_USERNAME=your_username
MAIL_PASSWORD=your_password
MAIL_FROM_ADDRESS=noreply@elimucore.local

LOG_CHANNEL=stack
LOG_LEVEL=warning
```

### Frontend (.env)

```env
VITE_API_BASE_URL=https://api.elimucore.local/api
VITE_APP_NAME=ElimuCore
VITE_APP_TITLE=School Management Information System
VITE_ENVIRONMENT=production
```

---

## Database Configuration

### MySQL Initial Setup

```sql
-- Create database
CREATE DATABASE elimucore CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- Create user
CREATE USER 'elimucore_user'@'localhost' IDENTIFIED BY 'strong_password';

-- Grant privileges
GRANT ALL PRIVILEGES ON elimucore.* TO 'elimucore_user'@'localhost';
GRANT ALL PRIVILEGES ON elimucore.* TO 'elimucore_user'@'%';

-- Apply changes
FLUSH PRIVILEGES;

-- Backup
mysqldump -u elimucore_user -p elimucore > backup.sql

-- Restore
mysql -u elimucore_user -p elimucore < backup.sql
```

---

## Monitoring & Maintenance

### Log Files

```bash
# Backend logs
tail -f /var/www/elimucore/backend/storage/logs/laravel.log

# Nginx access logs
tail -f /var/log/nginx/access.log

# Nginx error logs
tail -f /var/log/nginx/error.log

# PHP-FPM logs
tail -f /var/log/php8.3-fpm.log
```

### Performance Optimization

```bash
cd /var/www/elimucore/backend

# Clear caches
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear

# Rebuild caches
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Optimize composer
composer dump-autoload --optimize

# Clear old logs
php artisan tinker
# >>> \Log::flush();
```

### Database Backup

```bash
# Create backup directory
mkdir -p /var/backups/elimucore

# Daily backup script (/usr/local/bin/backup-elimucore.sh)
#!/bin/bash
TIMESTAMP=$(date +%Y%m%d_%H%M%S)
mysqldump -u elimucore_user -p$DB_PASSWORD elimucore | gzip > /var/backups/elimucore/backup_$TIMESTAMP.sql.gz
# Keep last 30 days
find /var/backups/elimucore -type f -mtime +30 -delete

# Make executable
chmod +x /usr/local/bin/backup-elimucore.sh

# Add to crontab
0 2 * * * /usr/local/bin/backup-elimucore.sh
```

---

## Docker Deployment Details

### docker-compose.yml

```yaml
version: '3.8'

services:
  db:
    image: mysql:8.0
    environment:
      MYSQL_ROOT_PASSWORD: root
      MYSQL_DATABASE: elimucore
      MYSQL_USER: elimucore_user
      MYSQL_PASSWORD: elimucore_password
    ports:
      - "3306:3306"
    volumes:
      - db_data:/var/lib/mysql
    healthcheck:
      test: ["CMD", "mysqladmin", "ping", "-h", "localhost"]
      interval: 10s
      timeout: 5s
      retries: 5

  backend:
    build:
      context: ./backend
      dockerfile: ../Dockerfile.backend
    ports:
      - "8000:8000"
    environment:
      APP_ENV: production
      APP_DEBUG: false
      DB_HOST: db
      DB_DATABASE: elimucore
      DB_USERNAME: elimucore_user
      DB_PASSWORD: elimucore_password
    depends_on:
      db:
        condition: service_healthy
    volumes:
      - ./backend/storage:/app/storage

  frontend:
    build:
      context: ./frontend
      dockerfile: ../Dockerfile.frontend
    ports:
      - "5173:5173"
    environment:
      VITE_API_BASE_URL: http://localhost:8000/api
    depends_on:
      - backend

  nginx:
    image: nginx:alpine
    ports:
      - "80:80"
      - "443:443"
    volumes:
      - ./nginx.conf:/etc/nginx/nginx.conf
      - ./ssl:/etc/nginx/ssl
    depends_on:
      - backend
      - frontend

volumes:
  db_data:
```

---

## Troubleshooting

### Backend Issues

**500 Error**
```bash
# Check logs
tail -f backend/storage/logs/laravel.log

# Verify permissions
sudo chown -R www-data:www-data backend/storage
sudo chmod -R 775 backend/storage

# Run migrations
php artisan migrate
```

**Database Connection Error**
```bash
# Test connection
php artisan tinker
>>> DB::connection()->getPdo();

# Check credentials in .env
cat .env | grep DB_
```

**Composer Issues**
```bash
# Clear cache
composer clear-cache

# Reinstall
composer install --no-cache

# Update
composer update
```

### Frontend Issues

**Blank Page**
```bash
# Check console for errors
# Open browser DevTools (F12)

# Check API connectivity
curl http://localhost:8000/api/auth/user

# Rebuild
npm run build

# Check environment variables
cat .env
```

**API Connection Error**
```bash
# Verify backend is running
php artisan serve

# Check VITE_API_BASE_URL in .env
# Test API endpoint
curl http://localhost:8000/api/auth/login -X POST
```

---

## Security Checklist

- ✅ Change default credentials
- ✅ Set strong database password
- ✅ Enable HTTPS/SSL
- ✅ Configure CORS properly
- ✅ Set proper file permissions
- ✅ Enable firewall
- ✅ Regular backups
- ✅ Monitor logs
- ✅ Update dependencies
- ✅ Use environment variables
- ✅ Enable rate limiting
- ✅ Set up logging

---

## Production Deployment Checklist

```
Backend:
  ☐ Update APP_ENV to production
  ☐ Set APP_DEBUG to false
  ☐ Configure real database
  ☐ Set up Redis
  ☐ Generate application key
  ☐ Run migrations
  ☐ Seed initial data
  ☐ Cache configuration
  ☐ Optimize autoloader
  ☐ Configure HTTPS
  ☐ Set proper file permissions
  ☐ Configure logging

Frontend:
  ☐ Update API base URL
  ☐ Build for production
  ☐ Configure asset caching
  ☐ Enable compression
  ☐ Set up HTTPS redirect

General:
  ☐ Configure DNS
  ☐ Set up monitoring
  ☐ Configure backups
  ☐ Set up alerts
  ☐ Test all endpoints
  ☐ Performance testing
  ☐ Security audit
```

---

## Support

For detailed API documentation: See [API_DOCUMENTATION.md](API_DOCUMENTATION.md)
For quick start guide: See [QUICK_START.md](QUICK_START.md)
For system overview: See [README.md](README.md)


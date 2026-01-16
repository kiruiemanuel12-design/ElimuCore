# ElimuCore - Quick Deployment Guide

## 🚀 Project Structure After Separation

```
ElimuCore/
├── backend/                 ← Laravel API (Port 8000)
├── frontend/                ← Vue 3 App (Port 5173)
├── docker-compose.yml       ← One-command deploy
├── Dockerfile.backend       ← Backend container
├── Dockerfile.frontend      ← Frontend container
└── DEPLOYMENT_GUIDE.md      ← Full deployment docs
```

---

## ⚡ Quick Start (3 Steps)

### 1️⃣ Backend Setup

```bash
cd backend
cp .env.example .env
composer install
php artisan migrate
php artisan db:seed
php artisan serve
```

**Access:** http://localhost:8000/api

### 2️⃣ Frontend Setup

```bash
cd frontend
cp .env.example .env
npm install
npm run dev
```

**Access:** http://localhost:5173

### 3️⃣ Login
- Email: `admin@elimucore.local`
- Password: `Admin@123`

---

## 🐳 Docker Deployment (Recommended)

### One Command Deploy

```bash
docker-compose up -d
```

### Access
- **Frontend:** http://localhost
- **API:** http://localhost:8000/api

### Database Credentials
- User: `elimucore_user`
- Password: `elimucore_password`
- Database: `elimucore`

### Stop Services

```bash
docker-compose down
```

---

## 🖥️ Server Deployment (VPS/Cloud)

### Requirements
- PHP 8.3+
- Node.js 18+
- MySQL 8.0+
- Nginx/Apache

### 1️⃣ Install Backend

```bash
cd /var/www/elimucore/backend
composer install --optimize-autoloader --no-dev
cp .env.example .env

# Edit .env with production settings:
# - DB_HOST, DB_USERNAME, DB_PASSWORD
# - APP_URL=https://api.elimucore.local

php artisan migrate --force
php artisan db:seed --force
php artisan config:cache
php artisan route:cache
```

### 2️⃣ Install Frontend

```bash
cd /var/www/elimucore/frontend
npm install
npm run build
```

### 3️⃣ Configure Nginx (API)

```nginx
server {
    listen 80;
    server_name api.elimucore.local;
    root /var/www/elimucore/backend/public;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php8.3-fpm.sock;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
    }
}
```

### 4️⃣ Configure Nginx (Frontend)

```nginx
server {
    listen 80;
    server_name elimucore.local;
    root /var/www/elimucore/frontend/dist;

    location / {
        try_files $uri $uri/ /index.html;
    }

    location ~* \.(js|css|png|jpg|jpeg|gif|ico|svg)$ {
        expires 1y;
        add_header Cache-Control "public, immutable";
    }
}
```

### 5️⃣ Enable Sites & Restart Nginx

```bash
sudo systemctl restart nginx
```

### 6️⃣ Setup HTTPS

```bash
sudo apt install certbot python3-certbot-nginx
sudo certbot certonly --nginx -d api.elimucore.local
sudo certbot certonly --nginx -d elimucore.local
```

---

## 📝 Environment Configuration

### Backend (.env)

```env
APP_NAME=ElimuCore
APP_ENV=production
APP_DEBUG=false
APP_URL=https://api.elimucore.local

DB_CONNECTION=mysql
DB_HOST=your-db-host
DB_DATABASE=elimucore
DB_USERNAME=elimucore_user
DB_PASSWORD=your_strong_password

CACHE_DRIVER=redis
QUEUE_CONNECTION=redis
```

### Frontend (.env)

```env
VITE_API_BASE_URL=https://api.elimucore.local/api
VITE_APP_NAME=ElimuCore
VITE_ENVIRONMENT=production
```

---

## 🔒 Security

- ✅ Change all default passwords
- ✅ Enable HTTPS/SSL
- ✅ Set proper file permissions
- ✅ Configure firewall
- ✅ Regular backups
- ✅ Monitor logs

---

## 📊 Database Backup

```bash
# Backup
mysqldump -u elimucore_user -p elimucore > backup.sql

# Restore
mysql -u elimucore_user -p elimucore < backup.sql

# Automated daily backup
# Add to crontab: 0 2 * * * mysqldump -u elimucore_user -p$PASS elimucore | gzip > /var/backups/backup_$(date +\%Y\%m\%d).sql.gz
```

---

## 🐛 Troubleshooting

| Issue | Solution |
|-------|----------|
| 500 Error | Check `backend/storage/logs/laravel.log` |
| DB Connection Error | Verify .env database credentials |
| Frontend Blank Page | Check VITE_API_BASE_URL in .env |
| CORS Error | Enable CORS in backend |

---

## 📚 Full Documentation

- **API Reference:** See [API_DOCUMENTATION.md](API_DOCUMENTATION.md)
- **Complete Guide:** See [DEPLOYMENT_GUIDE.md](DEPLOYMENT_GUIDE.md)
- **System Overview:** See [README.md](README.md)

---

## 🎯 Production Checklist

```
Backend:
  ☐ Update .env for production
  ☐ Set APP_DEBUG=false
  ☐ Configure real database
  ☐ Run migrations
  ☐ Cache configuration
  ☐ Setup HTTPS

Frontend:
  ☐ Update API URL
  ☐ npm run build
  ☐ Configure caching

General:
  ☐ Setup monitoring
  ☐ Configure backups
  ☐ Enable logging
  ☐ Test all endpoints
```

---

**Ready to deploy?** Start with Docker for the easiest path! 🚀

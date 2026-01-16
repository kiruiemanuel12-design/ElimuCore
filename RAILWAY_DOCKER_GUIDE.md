# Railway Docker Compose Optimization Guide

> How to optimize your docker-compose.yml for Railway.app deployment

## Current docker-compose.yml Status

✅ **Compatible with Railway** - No changes required!

Your current `docker-compose.yml` works perfectly on Railway because:

- ✅ Uses standard Docker Compose format
- ✅ Defines all 4 services (db, backend, frontend, nginx)
- ✅ Has proper service names and networks
- ✅ Includes environment variables
- ✅ Maps ports correctly

## Railway Auto-Detection

When you push to Railway, it automatically:

1. **Finds docker-compose.yml** in root directory
2. **Parses all services** (db, backend, frontend, nginx)
3. **Creates containers** in correct order
4. **Maps ports** for public access
5. **Sets environment** variables from Railway dashboard

## Optional: Production Optimizations

If you want to enhance for production, consider these updates:

### Option 1: Multi-Stage Builds (Faster)

**File:** `Dockerfile.backend`

```dockerfile
# Stage 1: Builder
FROM php:8.3-fpm-alpine AS builder
WORKDIR /app
COPY . .
RUN composer install --no-dev --optimize-autoloader

# Stage 2: Production
FROM php:8.3-fpm-alpine
WORKDIR /app
COPY --from=builder /app /app
# ... rest of config
```

**Benefits:** Smaller image size, faster deployment

### Option 2: Health Checks

**File:** `docker-compose.yml`

Add health checks to services:

```yaml
backend:
  # ... existing config
  healthcheck:
    test: ["CMD", "curl", "-f", "http://localhost:8000/api/health"]
    interval: 30s
    timeout: 10s
    retries: 3
    start_period: 40s

frontend:
  # ... existing config
  healthcheck:
    test: ["CMD", "curl", "-f", "http://localhost/"]
    interval: 30s
    timeout: 10s
    retries: 3

db:
  # ... existing config
  healthcheck:
    test: ["CMD", "mysqldump", "--user=root", "--password=$$MYSQL_ROOT_PASSWORD", "elimucore", "--no-data"]
    interval: 30s
    timeout: 10s
    retries: 3
```

### Option 3: Resource Limits

**File:** `docker-compose.yml`

```yaml
backend:
  deploy:
    resources:
      limits:
        cpus: '1'
        memory: 512M
      reservations:
        cpus: '0.5'
        memory: 256M
```

---

## Current docker-compose.yml

Location: `/workspaces/ElimuCore/docker-compose.yml`

### Services Defined:

1. **db** (MySQL 8.0)
   - Handles all data storage
   - Persistent volume for data
   - Environment: DB credentials

2. **backend** (PHP 8.3-FPM + Laravel)
   - REST API endpoints
   - Connected to MySQL
   - Port 8000 in dev

3. **frontend** (Node 18 + Nginx)
   - Vue 3 SPA build
   - Static file serving
   - Port 5173 in dev

4. **nginx** (Reverse Proxy)
   - Routes requests
   - SSL termination (on Railway)
   - Load balancing

---

## Railway Deployment: What Happens

```
┌─────────────────────────────────────────┐
│  You push to GitHub                     │
└────────────────┬────────────────────────┘
                 │
┌────────────────▼────────────────────────┐
│  Railway detects docker-compose.yml    │
└────────────────┬────────────────────────┘
                 │
┌────────────────▼────────────────────────┐
│  Creates 4 services:                    │
│  - db (MySQL)                           │
│  - backend (Laravel)                    │
│  - frontend (Vue)                       │
│  - nginx (Proxy)                        │
└────────────────┬────────────────────────┘
                 │
┌────────────────▼────────────────────────┐
│  Assigns public URLs:                   │
│  - https://your-app.railway.app         │
│  - (backend accessible via nginx)       │
└────────────────┬────────────────────────┘
                 │
┌────────────────▼────────────────────────┐
│  Runs migrations & seeds (if configured)│
└────────────────┬────────────────────────┘
                 │
┌────────────────▼────────────────────────┐
│  System LIVE! 🚀                        │
└─────────────────────────────────────────┘
```

---

## Environment Variables on Railway

### During Build
Railway reads from `docker-compose.yml`:
```yaml
environment:
  - DB_HOST=db
  - DB_PORT=3306
  - APP_DEBUG=false
```

### During Runtime
Variables from Railway Dashboard **override** docker-compose.yml:
```env
APP_KEY=base64:...
DB_PASSWORD=production_password
MYSQL_PASSWORD=production_password
```

**This means:**
- Keep defaults in docker-compose.yml for local dev
- Set production values in Railway dashboard
- Never commit secrets to git

---

## Ports on Railway

Your docker-compose.yml uses:
- Port 3306 (MySQL) - Internal only
- Port 8000 (Laravel) - Through nginx
- Port 5173 (Vite) - Through nginx
- Port 80/443 (Nginx) - Public facing

**Railway automatically:**
- Exposes port 80/443 to public
- Creates HTTPS (automatic SSL)
- Routes requests to nginx
- Internal services communicate via service name

---

## No Changes Needed!

✅ Your `docker-compose.yml` is **production-ready**

Simply:
1. Push code to GitHub
2. Go to Railway.app
3. Connect your repository
4. Set environment variables
5. Click Deploy

**That's it!** 🚀

---

## Optional: Fine-Tuning for Railway

If you want to optimize further, consider:

1. **Add Health Checks** - Better monitoring
2. **Set Resource Limits** - Better cost control
3. **Use Multi-Stage Builds** - Smaller images
4. **Add Logging** - Better debugging

But these are **optional** - your system works without them!

---

*For deployment steps, see RAILWAY_DEPLOYMENT.md*

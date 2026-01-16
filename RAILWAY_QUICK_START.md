# 🚀 Railway Deployment Quick Start

> **Deploy ElimuCore to production in under 5 minutes**

## ⚡ Super Quick Version

```
1. Go to https://railway.app → Sign up with GitHub
2. New Project → Deploy from GitHub → Select kiruiemanuel12-design/ElimuCore
3. Set environment variables (see RAILWAY_CONFIG.md)
4. Click "Deploy"
5. Run migrations: railway run php artisan migrate --force
6. Your app is LIVE! 🎉
```

---

## 📋 5-Minute Deployment Checklist

### Pre-Deployment (2 min)
- [ ] GitHub repository updated with latest code
- [ ] All code committed and pushed to main branch
- [ ] docker-compose.yml is in root directory
- [ ] README.md exists in root

### Step 1: Create Railway Account (1 min)
```
Website: https://railway.app
Method: Sign up with GitHub
Authorization: Allow Railway to access repos
```

### Step 2: Create Project (1 min)
```
Railroad Dashboard → New Project
→ Deploy from GitHub repo
→ Select: kiruiemanuel12-design/ElimuCore
→ Branch: main
→ Deploy
```

### Step 3: Configure Environment Variables (2 min)

**Backend Service Variables:**
```
APP_KEY=base64:YOUR_GENERATED_KEY
APP_ENV=production
APP_DEBUG=false
APP_URL=https://your-app.railway.app

DB_HOST=db
DB_DATABASE=elimucore
DB_USERNAME=elimucore_user
DB_PASSWORD=STRONG_PASSWORD
```

**Database Service Variables:**
```
MYSQL_DATABASE=elimucore
MYSQL_USER=elimucore_user
MYSQL_PASSWORD=STRONG_PASSWORD
MYSQL_ROOT_PASSWORD=ROOT_PASSWORD
```

**Frontend Service Variables:**
```
VITE_API_BASE_URL=https://your-app.railway.app/api
```

### Step 4: Wait for Deployment
```
⏳ Railway builds and deploys (3-5 minutes)
✅ Check "Deployments" tab for progress
✅ All 4 services should show "Success"
```

### Step 5: Run Database Setup
```bash
# Install Railway CLI (one-time)
npm i -g @railway/cli

# Login to Railway
railway login

# Link to your project
railway link

# Run migrations
railway run php artisan migrate --force

# Seed test data (optional)
railway run php artisan db:seed --class=DatabaseSeeder
```

---

## 🌐 Access Your App

After successful deployment:

| Component | URL |
|-----------|-----|
| Frontend | `https://your-app.railway.app` |
| API Docs | `https://your-app.railway.app/api/` |
| Test Endpoint | `https://your-app.railway.app/api/auth/user` |

**Test Credentials:**
- Email: `admin@elimucore.local`
- Password: `Admin@123`

---

## 🔑 Getting Your APP_KEY

**Option 1: From Local Installation**
```bash
# If you have Laravel installed locally
php artisan key:generate

# Copy the value from .env
# It looks like: base64:...
```

**Option 2: Generate Random**
```bash
# Using OpenSSL
openssl rand -base64 32

# Then prefix with "base64:" in Railway
```

---

## ✅ Verify Deployment

- [ ] Frontend loads at https://your-app.railway.app
- [ ] Login page displays correctly
- [ ] Can log in with admin@elimucore.local / Admin@123
- [ ] API endpoints respond: https://your-app.railway.app/api/auth/user
- [ ] Database is populated with seed data

---

## 🐛 Troubleshooting Quick Fixes

| Problem | Solution |
|---------|----------|
| Services won't start | Check Logs tab → Fix errors → Redeploy |
| Database connection fails | Ensure DB_HOST=db, not localhost |
| Frontend can't reach API | Set VITE_API_BASE_URL in frontend variables |
| Migrations error | Manually run: `railway run php artisan migrate --force` |
| Port conflicts | Railway handles ports automatically, no action needed |

---

## 💾 Database Backup

After going live:

```bash
# Download database backup
railway run mysqldump -u elimucore_user -p elimucore > backup.sql

# Or use Railway Dashboard → db → Create backup
```

---

## 📚 Full Documentation

For complete details, see:
- **RAILWAY_DEPLOYMENT.md** - Detailed step-by-step guide
- **RAILWAY_CONFIG.md** - All environment variables explained
- **RAILWAY_DOCKER_GUIDE.md** - Docker optimization tips

---

## 🎯 Next Steps After Deployment

1. **Change admin password** in admin dashboard
2. **Configure custom domain** (if you have one)
3. **Setup email notifications** (optional, add SMTP)
4. **Enable HTTPS** (automatic on Railway)
5. **Configure backups** (via Railway dashboard)
6. **Monitor performance** (Metrics tab)

---

## 💰 Cost Estimate

- **Compute:** $5-15/month (small app)
- **Database:** Included
- **Storage:** Included (20GB)
- **Bandwidth:** Included

Scales automatically as traffic grows.

---

## 🎉 You're Ready!

Your ElimuCore SMIS is production-ready. Deploy now to Railway.app in **5 minutes** and start using immediately! 🚀

---

**Questions?** Check RAILWAY_DEPLOYMENT.md or Railway docs at https://docs.railway.app

*Last Updated: January 16, 2026*

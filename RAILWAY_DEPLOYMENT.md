# 🚀 Deploy ElimuCore to Railway.app

> **Complete step-by-step guide to deploy your SMIS to production in 5 minutes**

## Why Railway?

✅ **Easiest setup** - No DevOps knowledge needed  
✅ **Docker-native** - Your docker-compose.yml works as-is  
✅ **GitHub integrated** - Auto-deploy on push  
✅ **Includes database** - MySQL included  
✅ **Auto HTTPS** - SSL certificate automatic  
✅ **Affordable** - $5-20/month for small apps  
✅ **Scalable** - Grow without infrastructure changes  

---

## 📋 Prerequisites

- [x] GitHub account with ElimuCore repository pushed
- [x] Railroad.app account (sign up: https://railway.app)
- [ ] Custom domain (optional, Railway gives free subdomain)
- [ ] 10 minutes

---

## 🎯 5-Step Deployment

### **STEP 1: Create Railway Account**

1. Go to **https://railway.app**
2. Click **"Sign up"**
3. Choose **"Sign up with GitHub"** (fastest)
4. Authorize Railway to access your repositories
5. ✅ Account created!

---

### **STEP 2: Create New Project**

1. Click **"New Project"** (top right)
2. Select **"Deploy from GitHub repo"**
3. Find and select **`kiruiemanuel12-design/ElimuCore`**
4. Choose **"main"** branch
5. Click **"Deploy"**

**Railway will automatically:**
- ✅ Detect docker-compose.yml
- ✅ Create 4 services (db, backend, frontend, nginx)
- ✅ Detect environment variables needed
- ✅ Start building and deploying

---

### **STEP 3: Configure Environment Variables**

While Railway is building, set up environment variables:

#### **For Backend (Laravel)**

In Railway dashboard → backend service → Variables tab, add:

```bash
APP_NAME=ElimuCore
APP_ENV=production
APP_KEY=base64:YOUR_KEY_HERE
APP_DEBUG=false
APP_URL=https://your-app.railway.app

DB_CONNECTION=mysql
DB_HOST=db
DB_PORT=3306
DB_DATABASE=elimucore
DB_USERNAME=elimucore_user
DB_PASSWORD=RANDOM_SECURE_PASSWORD

CACHE_DRIVER=database
SESSION_DRIVER=database
QUEUE_CONNECTION=database
```

**To generate APP_KEY:**
```bash
# Run locally first
php artisan key:generate

# Copy the value shown in .env
# It will look like: base64:xxx...
```

#### **For Frontend (Vue)**

In Railway dashboard → frontend service → Variables tab:

```bash
VITE_API_BASE_URL=https://api.your-app.railway.app/api
```

---

### **STEP 4: Configure Database**

1. Go to Railway Dashboard → **Select "db" service**
2. Go to **"Variables"** tab
3. Set:
   ```bash
   MYSQL_DATABASE=elimucore
   MYSQL_USER=elimucore_user
   MYSQL_PASSWORD=RANDOM_SECURE_PASSWORD
   MYSQL_ROOT_PASSWORD=ANOTHER_SECURE_PASSWORD
   ```

4. Go to **"Settings"** tab
5. Set **Storage**: `20GB` (for production data)

---

### **STEP 5: Run Database Migrations**

After all services are deployed:

1. Go to **backend service** → **"Deploy Logs"**
2. Look for the **URL** (should be like `https://backend-xxxxx.railway.app`)
3. Run migrations via SSH:

   **Option A: Railway CLI (Recommended)**
   ```bash
   # Install Railway CLI
   npm i -g @railway/cli
   
   # Login
   railway login
   
   # Link to your project
   railway link
   
   # Run migrations
   railway run php artisan migrate --force
   railway run php artisan db:seed --class=DatabaseSeeder
   ```

   **Option B: Direct API Call**
   ```bash
   # Use the backend URL to trigger migrations
   curl -X POST https://backend-xxxxx.railway.app/api/migrate
   ```

   **Option C: SSH (if available)**
   ```bash
   ssh into container and run:
   php artisan migrate --force
   ```

---

## 🌐 Access Your Deployed System

After all services are running:

### **Frontend (Public)**
```
https://your-app.railway.app
```

### **Backend API**
```
https://api.your-app.railway.app/api/
```

### **Test Credentials**
```
Email: admin@elimucore.local
Password: Admin@123
```

---

## 📊 Monitor Your Deployment

In Railway Dashboard:

1. **Deployments** - View all deployment history
2. **Logs** - Real-time logs for each service
3. **Metrics** - CPU, Memory, Bandwidth usage
4. **Settings** - Scale, restart, or delete services

---

## 🔧 Troubleshooting

### **Services won't start?**

1. Check **Logs** for error messages
2. Verify **Environment Variables** are set
3. Ensure **docker-compose.yml** is valid
4. Try **Redeploy** from Railway dashboard

### **Database connection errors?**

Ensure:
- `DB_HOST=db` (not localhost, not IP)
- `DB_PORT=3306` is correct
- `DB_USERNAME` and `DB_PASSWORD` match
- Database service has **Storage** configured

### **Frontend can't connect to API?**

Set frontend variables:
```bash
VITE_API_BASE_URL=https://api.your-app.railway.app/api
```

Rebuild frontend:
- Railway dashboard → frontend → Redeploy

### **SSL/HTTPS not working?**

Railway provides free SSL automatically. If it fails:
1. Go to service **Settings**
2. Check **Custom Domain** configuration
3. Add your domain and let Railway auto-configure

---

## 📝 Adding Custom Domain

### **Using Railway's Domain:**

You already have a free `railway.app` subdomain. No extra steps needed!

### **Using Your Own Domain:**

1. Buy domain from GoDaddy, Namecheap, etc.
2. Railway dashboard → service → **"Settings"** → **"Custom Domain"**
3. Add your domain (e.g., `elimucore.com`)
4. Railway shows DNS records to update
5. Update your domain registrar's DNS settings
6. ✅ Custom domain active in ~30 min

---

## 💰 Pricing

**What you'll pay:**

- **Compute:** $5-20/month (depends on traffic)
- **Database:** Included in plan
- **Storage:** Included in plan
- **Bandwidth:** Included

**Free Tier Benefits:**
- Build & test free ($5 credit)
- Pay only after exceeding credit

---

## 🔐 Security Checklist

Before going live:

- [ ] Change default admin password
- [ ] Set `APP_DEBUG=false` in production
- [ ] Use strong `DB_PASSWORD`
- [ ] Enable HTTPS (automatic on Railway)
- [ ] Setup CORS for your domain
- [ ] Configure rate limiting
- [ ] Enable audit logging (already in system)
- [ ] Setup backups

---

## 🚀 Going Live

1. **Test all features** at your Railway URL
2. **Configure custom domain** (if you have one)
3. **Update test credentials** in admin panel
4. **Backup initial database** from Railway
5. **Monitor logs** for first 24 hours
6. **Share URL** with users

---

## 📞 Support

- **Railway Docs:** https://docs.railway.app
- **Laravel Docs:** https://laravel.com/docs
- **Issues?** Check Logs in Railway dashboard

---

## ✅ You're Live!

Your ElimuCore SMIS is now **production-ready** on Railway!

🎉 Share your deployment URL with stakeholders  
📊 Monitor usage in Railway dashboard  
🔄 Updates auto-deploy when you push to GitHub  
🛡️ Railway handles security and scaling  

**Next:** Build on the success - add more features, optimize performance, and scale confidently!

---

**Happy deploying! 🚀**

*Last Updated: January 16, 2026*

# ElimuCore - AWS Free Tier Deployment Guide

## 📋 Overview

Deploy ElimuCore on **AWS Free Tier** (12 months free for new accounts).

### What You Get Free:
- **EC2**: t2.micro or t3.micro (750 hours/month = ~24/7 for one instance)
- **RDS MySQL**: db.t2.micro or db.t3.micro (750 hours/month)
- **Elastic IP**: 1 static IP address
- **Data Transfer**: 1 GB outbound/month
- **S3 Storage**: 5 GB (optional for file uploads)

**Total Cost: $0/month (for 12 months as a new account)**

---

## 🔑 Step 1: Create AWS Account

1. Go to [aws.amazon.com/free](https://aws.amazon.com/free)
2. Click **Create a Free Account**
3. Enter email, password, and account name
4. Add payment method (required but won't charge if within free tier)
5. Verify identity (phone call or SMS)
6. Choose **Basic Plan** (free)
7. Complete setup

---

## 🖥️ Step 2: Create EC2 Instance

### 2.1 Launch Instance

1. Go to **AWS Management Console** → **EC2**
2. Click **Launch Instance**
3. Configure:
   - **Name**: `elimucore-server`
   - **OS Image**: Ubuntu 24.04 LTS (free tier eligible)
   - **Instance Type**: `t2.micro` (FREE)
   - **Key Pair**: Create new → name it `elimucore-key.pem`
     - **Save the .pem file securely!**

### 2.2 Security Group Configuration

In the security group settings:

| Type | Protocol | Port | Source |
|------|----------|------|--------|
| SSH | TCP | 22 | Your IP (for security) |
| HTTP | TCP | 80 | 0.0.0.0/0 (anywhere) |
| HTTPS | TCP | 443 | 0.0.0.0/0 (anywhere) |
| MySQL | TCP | 3306 | Your EC2 Security Group ID |

**To find EC2 IP for MySQL access:**
- Copy your EC2 security group ID (starts with sg-)
- Use it as source for MySQL rule

### 2.3 Storage

- **Volume Size**: 20 GB (free tier includes 30 GB/month, so this is safe)
- **Volume Type**: gp2 or gp3

### 2.4 Launch

- Click **Launch Instance**
- Wait 2-3 minutes for instance to be running
- Note the **Public IPv4 address** (e.g., `54.123.45.67`)

---

## 📊 Step 3: Create RDS MySQL Database

### 3.1 Create DB Instance

1. Go to **AWS Management Console** → **RDS**
2. Click **Create database**
3. Configure:
   - **Engine**: MySQL (latest free tier version, usually 8.0.x)
   - **Edition**: MySQL Community
   - **Version**: Latest (e.g., 8.0.35)
   - **Template**: Free tier

### 3.2 Settings

- **DB instance identifier**: `elimucore-db`
- **Master username**: `admin`
- **Master password**: Generate a strong password (save it!)
  - Example: `Secure@Pass123!xyz`

### 3.3 Connectivity

- **Connectivity**: Private
- **VPC**: Default VPC
- **Subnet group**: default
- **Public access**: No
- **VPC security group**: 
  - Create new: name it `rds-security-group`
  - Allow inbound MySQL from your EC2 security group

### 3.4 Additional Configuration

- **Initial database name**: `elimucore`
- **Backup retention**: 7 days
- **Enable deletion protection**: No (for free tier)
- **Enable Enhanced monitoring**: No (not free)

### 3.5 Create Database

- Click **Create database**
- Wait 5-10 minutes for creation
- Note the **Endpoint** (e.g., `elimucore-db.xxxxx.us-east-1.rds.amazonaws.com`)

---

## 🔗 Step 4: Connect to EC2 Instance

### 4.1 Connect via SSH

**On your local machine:**

```bash
# Make key file readable only by you
chmod 400 elimucore-key.pem

# Connect to EC2
ssh -i elimucore-key.pem ubuntu@YOUR_EC2_PUBLIC_IP
```

Replace `YOUR_EC2_PUBLIC_IP` with your instance's public IP (e.g., `54.123.45.67`)

### 4.2 Update System

```bash
sudo apt update && sudo apt upgrade -y
```

---

## 🐳 Step 5: Install Docker & Docker Compose

```bash
# Install Docker
curl -fsSL https://get.docker.com -o get-docker.sh
sudo sh get-docker.sh

# Add ubuntu user to docker group
sudo usermod -aG docker ubuntu

# Install Docker Compose
sudo curl -L "https://github.com/docker/compose/releases/latest/download/docker-compose-$(uname -s)-$(uname -m)" -o /usr/local/bin/docker-compose
sudo chmod +x /usr/local/bin/docker-compose

# Verify installations
docker --version
docker-compose --version

# Log out and back in for group changes to take effect
exit
ssh -i elimucore-key.pem ubuntu@YOUR_EC2_PUBLIC_IP
```

---

## 📦 Step 6: Clone & Configure Project

### 6.1 Clone Repository

```bash
cd /home/ubuntu
git clone https://github.com/YOUR_USERNAME/ElimuCore.git
cd ElimuCore
```

### 6.2 Configure Backend .env

```bash
cd backend
cp .env.example .env
```

**Edit `.env` with RDS credentials:**

```bash
nano .env
```

Update these values:

```env
APP_ENV=production
APP_DEBUG=false
APP_URL=http://YOUR_EC2_PUBLIC_IP

DB_CONNECTION=mysql
DB_HOST=elimucore-db.xxxxx.us-east-1.rds.amazonaws.com
DB_PORT=3306
DB_DATABASE=elimucore
DB_USERNAME=admin
DB_PASSWORD=Secure@Pass123!xyz

CACHE_DRIVER=file
SESSION_DRIVER=file
```

**Save and exit**: Press `Ctrl+X`, then `Y`, then `Enter`

### 6.3 Configure Frontend .env

```bash
cd ../frontend
cp .env.example .env
nano .env
```

```env
VITE_API_BASE_URL=http://YOUR_EC2_PUBLIC_IP/api
```

**Save and exit**

---

## 🚀 Step 7: Deploy with Docker Compose

### 7.1 Build Images (takes 5-10 minutes)

```bash
cd /home/ubuntu/ElimuCore
docker-compose build
```

### 7.2 Run Containers

```bash
docker-compose up -d
```

### 7.3 Initialize Database

```bash
# Run migrations
docker-compose exec backend php artisan migrate

# Seed database
docker-compose exec backend php artisan db:seed

# Generate app key (if not in .env)
docker-compose exec backend php artisan key:generate
```

### 7.4 Verify Services

```bash
# Check running containers
docker-compose ps

# Check logs
docker-compose logs -f
```

---

## ✅ Step 8: Test Application

### Access Your App

1. **Frontend**: `http://YOUR_EC2_PUBLIC_IP`
2. **Backend API**: `http://YOUR_EC2_PUBLIC_IP/api`
3. **API Docs**: `http://YOUR_EC2_PUBLIC_IP/api/docs` (if available)

### Default Login

- **Email**: `admin@elimucore.local`
- **Password**: `Admin@123`

---

## 🌐 Step 9: Setup Custom Domain (Optional)

### 9.1 Get Domain

Use Route 53 or external registrar (Namecheap, GoDaddy, etc.)

### 9.2 Configure Route 53 (AWS)

1. Go to **Route 53**
2. **Create hosted zone** for your domain
3. Add **A record**:
   - Name: `your-domain.com`
   - Type: A
   - Value: Your EC2 Elastic IP

### 9.3 Update .env Files

```env
# Backend
APP_URL=https://your-domain.com

# Frontend
VITE_API_BASE_URL=https://your-domain.com/api
```

---

## 🔒 Step 10: Setup SSL/HTTPS (Free with Let's Encrypt)

### 10.1 Install Certbot

```bash
sudo apt install certbot python3-certbot-nginx -y
```

### 10.2 Get SSL Certificate

```bash
sudo certbot certonly --standalone -d your-domain.com -d www.your-domain.com
```

### 10.3 Update Docker nginx Config

Update `nginx.conf` to use SSL certificates:

```nginx
server {
    listen 443 ssl http2;
    server_name your-domain.com www.your-domain.com;

    ssl_certificate /etc/letsencrypt/live/your-domain.com/fullchain.pem;
    ssl_certificate_key /etc/letsencrypt/live/your-domain.com/privkey.pem;

    # ... rest of config
}

# Redirect HTTP to HTTPS
server {
    listen 80;
    server_name your-domain.com www.your-domain.com;
    return 301 https://$server_name$request_uri;
}
```

### 10.4 Renew Certificate Automatically

```bash
sudo systemctl enable certbot.timer
sudo systemctl start certbot.timer
```

---

## 📊 Step 11: Monitor Costs

### Enable Cost Alerts

1. Go to **AWS Billing Console**
2. Click **Billing Preferences**
3. Enable **Receive Free Tier Usage Alerts**
4. Set budget alerts at $0.10 (to catch overages early)

### Free Tier Dashboard

1. Go to **AWS Management Console**
2. Search for **Billing Dashboard**
3. Monitor usage in **Free Tier Usage** section

### What Costs Money (After Free Tier):

| Service | Free Tier Ends | Cost |
|---------|---|---|
| EC2 t2.micro | 12 months | ~$9/month |
| RDS MySQL | 12 months | ~$15/month |
| Data out | Always | $0.09/GB |
| Elastic IP (unused) | Always | $3.50/month |

**Pro Tip**: Before free tier ends, consider AWS Lightsail ($3.50/month for bundled instance+DB)

---

## 🛡️ Step 12: Security Best Practices

### 12.1 EC2 Security

✅ **Restrict SSH to your IP only**
- Don't allow 0.0.0.0/0 for port 22

✅ **Use security groups properly**
- Only open ports you need
- Use security group references for internal communication

✅ **Enable EC2 detailed monitoring**
- CloudWatch monitors for free tier

### 12.2 Database Security

✅ **Use strong passwords**
- At least 16 characters
- Mix uppercase, lowercase, numbers, symbols

✅ **Enable automated backups**
- 7-day retention (free tier default)

✅ **Restrict database access**
- Only allow from EC2 security group

### 12.3 Application Security

✅ **Update .env files**
- Never commit to git
- Use AWS Secrets Manager (free tier eligible)

✅ **Enable HTTPS**
- Use Let's Encrypt (free)

✅ **Regular backups**
```bash
# Backup database monthly
docker-compose exec backend php artisan backup:run
```

---

## 🔧 Useful Commands

### Start/Stop Services

```bash
# Start
docker-compose up -d

# Stop
docker-compose down

# Restart
docker-compose restart

# View logs
docker-compose logs -f backend
docker-compose logs -f frontend
```

### Database Operations

```bash
# Run migrations
docker-compose exec backend php artisan migrate

# Rollback
docker-compose exec backend php artisan migrate:rollback

# Fresh seed
docker-compose exec backend php artisan migrate:refresh --seed

# Backup database
mysqldump -h YOUR_RDS_ENDPOINT -u admin -p elimucore > backup.sql
```

### Update Application

```bash
cd /home/ubuntu/ElimuCore
git pull origin main
docker-compose build
docker-compose up -d
docker-compose exec backend php artisan migrate
```

---

## 📞 Troubleshooting

### "Can't connect to database"

```bash
# Check RDS endpoint is correct in .env
# Check RDS security group allows EC2 traffic
# Check EC2 can reach RDS:
mysql -h elimucore-db.xxxxx.us-east-1.rds.amazonaws.com -u admin -p
```

### "Frontend can't reach backend API"

```bash
# Verify VITE_API_BASE_URL in frontend/.env
# Check EC2 security group allows port 80/443
# Check containers are running:
docker-compose ps
```

### "Out of free tier limits"

1. Check **Billing Dashboard**
2. Review **Cost Explorer**
3. Stop unused resources:
   ```bash
   docker-compose down
   ```
4. Consider AWS Lightsail as alternative

---

## 📈 Next Steps

1. ✅ Test application thoroughly
2. ✅ Setup automated backups
3. ✅ Configure monitoring & alerts
4. ✅ Setup CI/CD pipeline (GitHub Actions)
5. ✅ Plan for free tier end (migrate to Lightsail or paid tier)

---

## 🆘 Support Resources

- **AWS Free Tier FAQs**: https://aws.amazon.com/free/free-tier-faqs/
- **EC2 User Guide**: https://docs.aws.amazon.com/ec2/
- **RDS Documentation**: https://docs.aws.amazon.com/rds/
- **Docker Compose Docs**: https://docs.docker.com/compose/

---

**Last Updated**: January 16, 2026
**Project**: ElimuCore SMIS
**Status**: Ready for Production

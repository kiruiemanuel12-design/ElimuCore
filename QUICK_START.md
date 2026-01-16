# ElimuCore - Quick Start Guide

## System Requirements
- PHP 8.3 or higher
- Composer
- SQLite or MySQL/PostgreSQL

## Installation

### 1. Clone Repository
```bash
git clone <repository-url>
cd ElimuCore
```

### 2. Install Dependencies
```bash
composer install
```

### 3. Environment Setup
```bash
# Copy environment configuration
cp .env.example .env

# Generate application key
php artisan key:generate
```

### 4. Database Setup
```bash
# For SQLite (development)
touch database/database.sqlite

# Run migrations
php artisan migrate

# Seed initial data
php artisan db:seed
```

### 5. Start Development Server
```bash
php artisan serve
```

The application will be available at `http://localhost:8000`

---

## Default Credentials

### Super Admin
- **Email:** admin@elimucore.local
- **Password:** Admin@123
- **Role:** Super Admin

### Sample Accounts
- **Principal:** principal@elimucore.local / Principal@123
- **Teacher:** teacher@elimucore.local / Teacher@123
- **Bursar:** bursar@elimucore.local / Bursar@123

---

## API Testing

### 1. Login and Get Token
```bash
curl -X POST http://localhost:8000/api/auth/login \
  -H "Content-Type: application/json" \
  -d '{
    "email": "admin@elimucore.local",
    "password": "Admin@123"
  }'
```

**Response:**
```json
{
  "message": "Login successful",
  "access_token": "YOUR_TOKEN_HERE",
  "user": { ... }
}
```

### 2. Use Token in API Requests
```bash
curl -X GET http://localhost:8000/api/user \
  -H "Authorization: Bearer YOUR_TOKEN_HERE" \
  -H "Content-Type: application/json"
```

### 3. List Students
```bash
curl -X GET http://localhost:8000/api/students \
  -H "Authorization: Bearer YOUR_TOKEN_HERE" \
  -H "Content-Type: application/json"
```

### 4. Register New User
```bash
curl -X POST http://localhost:8000/api/auth/register \
  -H "Content-Type: application/json" \
  -d '{
    "name": "New User",
    "email": "newuser@school.local",
    "phone": "+254700000010",
    "password": "Password@123",
    "password_confirmation": "Password@123",
    "role": "teacher"
  }'
```

---

## Production Deployment

### 1. Environment Configuration
Update `.env` for production:
```env
APP_ENV=production
APP_DEBUG=false
APP_URL=https://yourdomain.com

DB_CONNECTION=mysql
DB_HOST=your-db-host
DB_DATABASE=elimucore
DB_USERNAME=db_user
DB_PASSWORD=secure_password
```

### 2. Run Migrations
```bash
php artisan migrate --force
```

### 3. Generate Optimized Autoload
```bash
composer dump-autoload --optimize
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

### 4. Web Server Configuration
Configure your web server (Nginx/Apache) to point to the `public` directory.

**Nginx Example:**
```nginx
server {
    listen 80;
    server_name yourdomain.com;
    root /path/to/ElimuCore/public;
    index index.php;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php-fpm.sock;
        fastcgi_index index.php;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
    }
}
```

---

## Development Commands

### Database
```bash
# Run migrations
php artisan migrate

# Rollback last batch
php artisan migrate:rollback

# Refresh database (WARNING: Data loss!)
php artisan migrate:refresh

# Seed database
php artisan db:seed

# Reset and seed
php artisan migrate:fresh --seed
```

### Code Generation
```bash
# Create model with migration
php artisan make:model ModelName --migration

# Create controller
php artisan make:controller ControllerName --api

# Create seeder
php artisan make:seeder SeederName

# Create test
php artisan make:test TestName
```

### Maintenance
```bash
# Cache clear
php artisan cache:clear

# Config cache
php artisan config:cache

# Route cache
php artisan route:cache

# View cache
php artisan view:cache
```

---

## Troubleshooting

### Database Connection Error
```bash
# Check database file exists and is writable
touch database/database.sqlite
chmod 666 database/database.sqlite
```

### Permission Denied Errors
```bash
# Fix storage and bootstrap permissions
chmod -R 775 storage bootstrap/cache

# On some systems, may need
sudo chown -R www-data:www-data /path/to/elimucore
```

### Composer Issues
```bash
# Clear composer cache
composer clear-cache

# Update dependencies
composer update

# Check for issues
composer diagnose
```

### Queue Issues
```bash
# Process queue jobs
php artisan queue:work

# Clear failed jobs
php artisan queue:flush
```

---

## File Structure
```
ElimuCore/
├── app/
│   ├── Models/              # Eloquent models
│   └── Http/Controllers/    # API controllers
├── database/
│   ├── migrations/          # Database migrations
│   └── seeders/             # Database seeders
├── routes/
│   ├── api.php              # API routes
│   └── web.php              # Web routes
├── config/                  # Configuration files
├── tests/                   # Test files
├── public/                  # Public assets
├── storage/                 # Storage directory
├── bootstrap/               # Bootstrap files
├── vendor/                  # Composer packages
└── .env                     # Environment configuration
```

---

## Key Features

✅ **Role-Based Access Control** - 8 user roles with granular permissions
✅ **Approval Workflows** - Secure user onboarding with admin review
✅ **Attendance Tracking** - Daily student attendance with reports
✅ **Financial Management** - Fees, payments, and payroll tracking
✅ **Academic Records** - Grade management and performance analytics
✅ **Audit Logging** - Complete action history for compliance
✅ **Mobile-Ready APIs** - Stateless authentication for mobile apps
✅ **Secure Authentication** - Laravel Sanctum token-based auth
✅ **Comprehensive Reporting** - Enrollment, attendance, financial reports

---

## Support & Documentation

- **API Documentation**: See `API_DOCUMENTATION.md`
- **README**: See `README.md`
- **Code Comments**: Inline documentation in source files

---

## Contact

For support, issues, or feature requests:
- Email: support@elimucore.local
- Documentation: https://docs.elimucore.local

---

**Version:** 1.0.0  
**Last Updated:** January 16, 2026

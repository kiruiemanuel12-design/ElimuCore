# ElimuCore SMIS - Separated Architecture

A complete School Management Information System with decoupled frontend and backend for easy deployment.

## 📁 Architecture Overview

```
ElimuCore/
├── backend/                    # Laravel 12 REST API
│   ├── app/                   # Controllers, Models, etc.
│   ├── database/              # Migrations, Seeders
│   ├── config/                # Configuration files
│   ├── routes/                # API routes
│   ├── composer.json
│   ├── .env.example
│   └── artisan
│
├── frontend/                   # Vue 3 + Vite Single Page App
│   ├── src/
│   │   ├── components/        # Vue components
│   │   ├── stores/            # Pinia state management
│   │   ├── views/             # Page components
│   │   ├── router/            # Vue Router configuration
│   │   ├── api.js             # Axios instance
│   │   └── main.js            # App entry point
│   ├── package.json
│   ├── vite.config.js
│   ├── .env.example
│   └── index.html
│
├── docker-compose.yml          # Complete stack in Docker
├── Dockerfile.backend          # Backend container definition
├── Dockerfile.frontend         # Frontend container definition
├── nginx.conf                  # Nginx configuration
│
├── QUICK_DEPLOYMENT.md         # Quick start guide
├── DEPLOYMENT_GUIDE.md         # Complete deployment instructions
├── API_DOCUMENTATION.md        # API endpoint reference
├── PROJECT_COMPLETION_REPORT.md # System overview
└── README.md                   # This file
```

## 🚀 Quick Start

### Option 1: Docker (Recommended for Development & Deployment)

```bash
# Start all services
docker-compose up -d

# Access:
# Frontend: http://localhost
# API: http://localhost:8000/api
# Database: mysql://elimucore_user:elimucore_password@localhost:3306/elimucore
```

### Option 2: Local Development

#### Backend
```bash
cd backend
cp .env.example .env
composer install
php artisan migrate
php artisan db:seed
php artisan serve
# API runs on http://localhost:8000
```

#### Frontend (in another terminal)
```bash
cd frontend
cp .env.example .env
npm install
npm run dev
# Frontend runs on http://localhost:5173
```

### Test Login
- **Email:** admin@elimucore.local
- **Password:** Admin@123

---

## 🛠️ Technology Stack

### Backend
- **Framework:** Laravel 12
- **Language:** PHP 8.3
- **Database:** MySQL 8.0 / PostgreSQL / SQLite
- **Authentication:** Laravel Sanctum (API tokens)
- **API Format:** RESTful JSON

### Frontend
- **Framework:** Vue 3
- **Build Tool:** Vite
- **State Management:** Pinia
- **HTTP Client:** Axios
- **Styling:** Tailwind CSS (ready to install)
- **Router:** Vue Router 4

### DevOps
- **Containerization:** Docker
- **Orchestration:** Docker Compose
- **Web Server:** Nginx
- **Database:** MySQL 8.0

---

## 📚 Documentation

### Getting Started
- **[QUICK_DEPLOYMENT.md](QUICK_DEPLOYMENT.md)** - Fast deployment steps (3-5 min read)

### Deployment
- **[DEPLOYMENT_GUIDE.md](DEPLOYMENT_GUIDE.md)** - Complete deployment procedures
  - Docker setup
  - Server/VPS deployment
  - HTTPS/SSL configuration
  - Database configuration
  - Monitoring & backups

### API Reference
- **[API_DOCUMENTATION.md](API_DOCUMENTATION.md)** - All 35+ endpoints documented
  - Request/response examples
  - Authentication methods
  - Error handling
  - Rate limiting

### System Overview
- **[PROJECT_COMPLETION_REPORT.md](PROJECT_COMPLETION_REPORT.md)** - Complete project status
  - Architecture overview
  - Feature checklist
  - Database schema
  - User roles & permissions

---

## 🔑 Key Features

### User Management
- 8 user roles (Super Admin, Principal, Deputy Academic, Deputy Admin, Teacher, Bursar, Student, Parent)
- Role-based access control (RBAC)
- Approval workflow for user onboarding
- Multi-factor authentication ready

### Student Management
- Student registration & admission
- Class level & stream assignment
- Guardian management
- Approval workflow

### Staff Management
- Staff registration & profiles
- TSC/BOM authority tracking
- Payroll management
- Salary tracking

### Attendance
- Daily attendance recording
- Per-subject tracking
- Multiple statuses (present/absent/excused/late)
- Attendance reports

### Fees
- Fee structure management
- Payment recording & verification
- Arrears tracking
- Collection reports

### Payroll
- Monthly salary generation
- Salary components (basic, allowances, deductions)
- Approval workflow
- Payment tracking

### Academic
- Grade recording
- Performance analysis
- Marks to grade conversion
- Academic reports

### Reporting
- Enrollment statistics
- Attendance analysis
- Fee collection reports
- Payroll summaries
- Custom report generation

### Security
- Audit logging for all actions
- User action tracking
- IP address logging
- Compliance reporting

---

## 📊 API Endpoints (35+)

### Authentication
```
POST   /api/auth/register     - User registration
POST   /api/auth/login        - User login
POST   /api/auth/logout       - User logout
GET    /api/auth/user         - Get current user
PUT    /api/auth/profile      - Update profile
POST   /api/auth/change-password
```

### Students
```
GET    /api/students          - List all students
POST   /api/students          - Create new student
GET    /api/students/{id}     - Get student details
PUT    /api/students/{id}     - Update student
DELETE /api/students/{id}     - Delete student
GET    /api/students/{id}/attendance
GET    /api/students/{id}/fees
GET    /api/students/{id}/grades
GET    /api/students/{id}/guardians
```

### Staff
```
GET    /api/staff             - List all staff
POST   /api/staff             - Create new staff
GET    /api/staff/{id}        - Get staff details
PUT    /api/staff/{id}        - Update staff
DELETE /api/staff/{id}        - Delete staff
GET    /api/staff/{id}/payroll
```

### Attendance
```
POST   /api/attendance        - Record attendance
POST   /api/attendance/bulk   - Bulk record
GET    /api/attendance/class/{id}
GET    /api/attendance/student/{id}
GET    /api/attendance/report
```

### Fees & Payments
```
GET    /api/fees              - List fees
POST   /api/fees              - Create fee
POST   /api/fees/{id}/payment - Record payment
GET    /api/fees/student/{id}
GET    /api/fees/arrears      - Arrears report
GET    /api/fees/collection   - Collection summary
```

### Payroll
```
GET    /api/payroll           - List payroll
POST   /api/payroll           - Generate payroll
GET    /api/payroll/{id}      - Get details
PUT    /api/payroll/{id}/approve
POST   /api/payroll/{id}/pay
POST   /api/payroll/bulk-generate
```

### Reports
```
GET    /api/reports           - List reports
POST   /api/reports           - Generate report
GET    /api/reports/enrollment
GET    /api/reports/attendance
GET    /api/reports/fees
GET    /api/reports/payroll
GET    /api/reports/academic
GET    /api/reports/discipline
```

### Approvals
```
GET    /api/approvals         - List approvals
GET    /api/approvals/pending
PUT    /api/approvals/{id}/approve
PUT    /api/approvals/{id}/reject
```

---

## 🔐 Default Credentials

After running `db:seed`, the following test accounts are created:

| Role | Email | Password |
|------|-------|----------|
| Super Admin | admin@elimucore.local | Admin@123 |
| Principal | principal@elimucore.local | Principal@123 |
| Teacher | teacher@elimucore.local | Teacher@123 |
| Bursar | bursar@elimucore.local | Bursar@123 |

⚠️ **Change these in production!**

---

## 🌐 Environment Variables

### Backend (.env)
```env
APP_NAME=ElimuCore
APP_ENV=production
APP_DEBUG=false
APP_URL=https://api.elimucore.local

DB_CONNECTION=mysql
DB_HOST=localhost
DB_DATABASE=elimucore
DB_USERNAME=elimucore_user
DB_PASSWORD=password

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

## 📦 Docker Deployment

### Quick Start
```bash
docker-compose up -d
```

### Useful Commands
```bash
# View logs
docker-compose logs -f

# Run migrations
docker-compose exec backend php artisan migrate

# Run seeder
docker-compose exec backend php artisan db:seed

# Access backend shell
docker-compose exec backend bash

# Stop services
docker-compose down

# Stop and remove volumes
docker-compose down -v
```

---

## 🖥️ Server Deployment

See [DEPLOYMENT_GUIDE.md](DEPLOYMENT_GUIDE.md) for complete instructions on:
- Manual VPS/Server setup
- Nginx configuration
- HTTPS/SSL setup
- Database configuration
- Monitoring & backups

---

## 🔄 Development Workflow

### Running Tests
```bash
cd backend
php artisan test
```

### Code Quality
```bash
# Backend
cd backend
./vendor/bin/phpstan analyse
./vendor/bin/phpcs --standard=PSR12 app

# Frontend
cd frontend
npm run lint
```

### Building for Production
```bash
# Backend
cd backend
composer install --optimize-autoloader --no-dev
php artisan config:cache
php artisan route:cache

# Frontend
cd frontend
npm run build
# Output in dist/ directory
```

---

## 🐛 Troubleshooting

### Backend won't start
```bash
cd backend
php artisan migrate:fresh --seed
php artisan cache:clear
php artisan serve
```

### Frontend shows blank page
- Check browser console for errors (F12)
- Verify VITE_API_BASE_URL in frontend/.env
- Ensure backend is running and accessible

### Database connection error
```bash
# Test connection
cd backend
php artisan tinker
>>> DB::connection()->getPdo();
```

### Docker issues
```bash
# Rebuild containers
docker-compose down -v
docker-compose build --no-cache
docker-compose up -d
```

See [DEPLOYMENT_GUIDE.md](DEPLOYMENT_GUIDE.md) for more troubleshooting.

---

## 📈 Performance Optimization

### Backend
- ✅ Query optimization with eager loading
- ✅ Redis caching configured
- ✅ Database indexing
- ✅ Route caching
- ✅ Config caching

### Frontend
- ✅ Code splitting with Vite
- ✅ Asset caching headers
- ✅ Gzip compression
- ✅ Lazy loading components
- ✅ Tree-shaking unused code

---

## 🔒 Security Features

- ✅ CORS configuration
- ✅ CSRF protection
- ✅ SQL injection prevention (Eloquent ORM)
- ✅ Password hashing (bcrypt)
- ✅ API token authentication
- ✅ Role-based access control
- ✅ Input validation
- ✅ Audit logging

---

## 📞 Support

### Documentation
- [API Documentation](API_DOCUMENTATION.md)
- [Deployment Guide](DEPLOYMENT_GUIDE.md)
- [Quick Start](QUICK_DEPLOYMENT.md)
- [Project Report](PROJECT_COMPLETION_REPORT.md)

### Common Issues
See [DEPLOYMENT_GUIDE.md](DEPLOYMENT_GUIDE.md#troubleshooting) Troubleshooting section

---

## 📝 License

This project is created for educational purposes.

---

## ✅ Project Status

**Version:** 1.0.0  
**Status:** ✅ Production Ready  
**Last Updated:** January 16, 2026

The system is fully implemented, documented, and ready for deployment.


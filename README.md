# ElimuCore - School Management Information System (SMIS)

A comprehensive, API-first School Management Information System designed to digitize and centralize all core school operations. Built with Laravel, it supports public and private secondary schools with governance aligned to TSC (Teachers Service Commission) and BOM (Board of Management) structures.

## 🎯 System Overview

ElimuCore provides:
- **Role-based Access Control (RBAC)** - 8 distinct user roles with hierarchical permissions
- **Approval-based Onboarding** - Secure user registration with admin review
- **Attendance Management** - Daily tracking per student, subject, and class
- **Financial Management** - Comprehensive fees and payroll tracking
- **Academic Records** - Grade management and performance tracking
- **Audit Logging** - Complete action history for compliance
- **Mobile-Ready APIs** - Stateless authentication for mobile app integration

## 🏛️ Governance Model

### TSC Authority (Teachers Service Commission)
- **Principal**: Academic & staff oversight
- **Deputy Academic**: Curriculum & attendance management
- **Teacher**: Teaching, attendance, and grading

### BOM Authority (Board of Management)
- **Deputy Admin**: Operations & discipline
- **Bursar**: Fees, payroll, and financial reporting

### Super Admin
- System owner with full access and policy control

## 🔐 Authentication & Security

### Login Flow
1. User submits email & password
2. System verifies credentials
3. System checks approval status
4. Access granted only if approved
5. Role determines dashboard & permissions

### Features
- Laravel Sanctum token-based authentication
- Password hashing with bcrypt
- API token management
- Last login tracking
- Account activation/deactivation

## 📚 Database Schema

### Core Tables
- **Users** - Email, phone, role, approval status
- **Students** - Admission number, class, stream, guardians
- **Staff** - Staff number, position, authority (TSC/BOM)
- **Attendance** - Daily tracking with status and remarks
- **Fees & Payments** - Fee structures and payment records
- **Payroll** - Monthly compensation and approval workflow
- **Guardians** - Student contact information
- **Grades** - Academic performance tracking
- **Audit Logs** - Complete action history
- **Approvals** - User onboarding workflow
- **Reports** - Generated system reports

## 🔌 API Endpoints

### Authentication
- `POST /api/auth/register` - Register new user
- `POST /api/auth/login` - Login with email & password
- `POST /api/auth/logout` - Logout
- `GET /api/user` - Get authenticated user
- `PUT /api/user` - Update profile
- `POST /api/user/change-password` - Change password

### Students
- `GET /api/students` - List students
- `POST /api/students` - Create student
- `GET /api/students/{id}` - Get student details
- `GET /api/students/{id}/attendance` - Attendance history
- `GET /api/students/{id}/fees` - Fee records
- `GET /api/students/{id}/grades` - Student grades

### Staff
- `GET /api/staff` - List staff
- `POST /api/staff` - Create staff member
- `GET /api/staff/{id}/payroll` - Payroll history

### Attendance
- `POST /api/attendance` - Record attendance
- `POST /api/attendance/bulk` - Record bulk attendance
- `GET /api/attendance/reports` - Attendance reports

### Fees & Payments
- `POST /api/fees/{id}/record-payment` - Record payment
- `GET /api/fees/arrears` - Arrears report
- `GET /api/fees/collection-summary` - Collection summary

### Payroll
- `POST /api/payroll/{id}/approve` - Approve payroll
- `POST /api/payroll/{id}/pay` - Process payment
- `POST /api/payroll/bulk-generate` - Generate monthly

### Reports
- `POST /api/reports/generate` - Generate report
- `GET /api/reports/enrollment` - Enrollment statistics
- `GET /api/reports/attendance` - Attendance analysis
- `GET /api/reports/fees` - Fee collection
- `GET /api/reports/payroll` - Payroll expenditure

## 📊 User Roles

| Role | Authority | Key Permissions |
|------|-----------|-----------------|
| Super Admin | BOTH | Full system access |
| Principal | TSC | Staff management, student approval |
| Deputy Academic | TSC | Attendance, grades, curriculum |
| Deputy Admin | BOM | Operations, discipline |
| Teacher | TSC | Attendance, grades, students |
| Bursar | BOM | Fees, payments, payroll |
| Student | - | View own records |
| Parent | - | View child's records |

## 🚀 Quick Start

```bash
# Install dependencies
composer install

# Configure environment
cp .env.example .env
php artisan key:generate

# Setup database
touch database/database.sqlite
php artisan migrate

# Start server
php artisan serve
```

## 📝 Default Admin

- **Email**: admin@elimucore.local
- **Password**: Admin@123

## 🔒 Security Features

- Role-based access control
- Approval workflows for new users
- Secure password hashing
- API token authentication
- Audit logging for compliance
- IP address tracking

## 📞 Support & Documentation

Full documentation: See inline code comments and API routes
Report Issues: Create GitHub issues
Contact: support@elimucore.local

## 📄 License

MIT License - See LICENSE.md for details

---

**ElimuCore v1.0** - Production Ready ✅


Laravel is a web application framework with expressive, elegant syntax. We believe development must be an enjoyable and creative experience to be truly fulfilling. Laravel takes the pain out of development by easing common tasks used in many web projects, such as:

- [Simple, fast routing engine](https://laravel.com/docs/routing).
- [Powerful dependency injection container](https://laravel.com/docs/container).
- Multiple back-ends for [session](https://laravel.com/docs/session) and [cache](https://laravel.com/docs/cache) storage.
- Expressive, intuitive [database ORM](https://laravel.com/docs/eloquent).
- Database agnostic [schema migrations](https://laravel.com/docs/migrations).
- [Robust background job processing](https://laravel.com/docs/queues).
- [Real-time event broadcasting](https://laravel.com/docs/broadcasting).

Laravel is accessible, powerful, and provides tools required for large, robust applications.

## Learning Laravel

Laravel has the most extensive and thorough [documentation](https://laravel.com/docs) and video tutorial library of all modern web application frameworks, making it a breeze to get started with the framework. You can also check out [Laravel Learn](https://laravel.com/learn), where you will be guided through building a modern Laravel application.

If you don't feel like reading, [Laracasts](https://laracasts.com) can help. Laracasts contains thousands of video tutorials on a range of topics including Laravel, modern PHP, unit testing, and JavaScript. Boost your skills by digging into our comprehensive video library.

## Laravel Sponsors

We would like to extend our thanks to the following sponsors for funding Laravel development. If you are interested in becoming a sponsor, please visit the [Laravel Partners program](https://partners.laravel.com).

### Premium Partners

- **[Vehikl](https://vehikl.com)**
- **[Tighten Co.](https://tighten.co)**
- **[Kirschbaum Development Group](https://kirschbaumdevelopment.com)**
- **[64 Robots](https://64robots.com)**
- **[Curotec](https://www.curotec.com/services/technologies/laravel)**
- **[DevSquad](https://devsquad.com/hire-laravel-developers)**
- **[Redberry](https://redberry.international/laravel-development)**
- **[Active Logic](https://activelogic.com)**

## Contributing

Thank you for considering contributing to the Laravel framework! The contribution guide can be found in the [Laravel documentation](https://laravel.com/docs/contributions).

## Code of Conduct

In order to ensure that the Laravel community is welcoming to all, please review and abide by the [Code of Conduct](https://laravel.com/docs/contributions#code-of-conduct).

## Security Vulnerabilities

If you discover a security vulnerability within Laravel, please send an e-mail to Taylor Otwell via [taylor@laravel.com](mailto:taylor@laravel.com). All security vulnerabilities will be promptly addressed.

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).

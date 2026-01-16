# ElimuCore SMIS - Complete API Documentation

## Table of Contents
1. [Authentication](#authentication)
2. [Students](#students)
3. [Staff Management](#staff-management)
4. [Attendance](#attendance)
5. [Fees & Payments](#fees--payments)
6. [Payroll](#payroll)
7. [Reports](#reports)
8. [Approvals](#approvals)
9. [Error Handling](#error-handling)

---

## Authentication

### 1. Register New User
**Endpoint:** `POST /api/auth/register`

**Description:** Register a new user in the system. Users must be approved before accessing the system.

**Request Body:**
```json
{
  "name": "John Doe",
  "email": "john@school.local",
  "phone": "+254700000001",
  "password": "SecurePass123",
  "password_confirmation": "SecurePass123",
  "role": "teacher"
}
```

**Role Options:** `student`, `parent`, `teacher`

**Success Response (201):**
```json
{
  "message": "User registered successfully. Awaiting approval.",
  "user": {
    "id": 5,
    "name": "John Doe",
    "email": "john@school.local",
    "phone": "+254700000001",
    "role": "teacher"
  }
}
```

**Error Response (422):**
```json
{
  "message": "The given data was invalid.",
  "errors": {
    "email": ["The email has already been taken."],
    "phone": ["The phone has already been taken."]
  }
}
```

---

### 2. Login
**Endpoint:** `POST /api/auth/login`

**Description:** Authenticate user and receive API token.

**Request Body:**
```json
{
  "email": "admin@elimucore.local",
  "password": "Admin@123"
}
```

**Success Response (200):**
```json
{
  "message": "Login successful",
  "access_token": "2|VaYZNTx8VjGLKFj...",
  "user": {
    "id": 1,
    "name": "System Administrator",
    "email": "admin@elimucore.local",
    "phone": "+254700000000",
    "role": "super_admin",
    "is_approved": true
  }
}
```

**Error - Unapproved Account (403):**
```json
{
  "message": "Your account is pending approval. Please wait for admin review.",
  "status": "pending_approval"
}
```

**Error - Invalid Credentials (422):**
```json
{
  "message": "The given data was invalid.",
  "errors": {
    "email": ["The provided credentials are incorrect."]
  }
}
```

---

### 3. Logout
**Endpoint:** `POST /api/auth/logout`

**Headers:** `Authorization: Bearer {token}`

**Success Response (200):**
```json
{
  "message": "Logged out successfully"
}
```

---

### 4. Get Current User
**Endpoint:** `GET /api/user`

**Headers:** `Authorization: Bearer {token}`

**Success Response (200):**
```json
{
  "id": 1,
  "name": "System Administrator",
  "email": "admin@elimucore.local",
  "phone": "+254700000000",
  "role": "super_admin",
  "is_approved": true,
  "is_active": true
}
```

---

### 5. Update Profile
**Endpoint:** `PUT /api/user`

**Headers:** `Authorization: Bearer {token}`

**Request Body:**
```json
{
  "name": "John Doe Updated",
  "phone": "+254700000005"
}
```

**Success Response (200):**
```json
{
  "message": "Profile updated successfully",
  "user": {
    "id": 5,
    "name": "John Doe Updated",
    "email": "john@school.local",
    "phone": "+254700000005",
    "role": "teacher"
  }
}
```

---

### 6. Change Password
**Endpoint:** `POST /api/user/change-password`

**Headers:** `Authorization: Bearer {token}`

**Request Body:**
```json
{
  "current_password": "OldPassword123",
  "new_password": "NewPassword123",
  "new_password_confirmation": "NewPassword123"
}
```

**Success Response (200):**
```json
{
  "message": "Password changed successfully"
}
```

---

## Students

### 1. List All Students
**Endpoint:** `GET /api/students`

**Headers:** `Authorization: Bearer {token}`

**Query Parameters:**
- `page` - Pagination page number (default: 1)
- `per_page` - Items per page (default: 15)
- `class_level_id` - Filter by class level
- `stream_id` - Filter by stream
- `status` - Filter by status (active, transferred, graduated, withdrawn)
- `search` - Search by admission number or name

**Success Response (200):**
```json
{
  "data": [
    {
      "id": 1,
      "user_id": 10,
      "admission_number": "ADM001",
      "national_id": "12345678",
      "class_level_id": 1,
      "stream_id": 1,
      "date_of_birth": "2008-05-15",
      "gender": "male",
      "phone": "+254700001001",
      "admission_date": "2023-01-15",
      "status": "active",
      "is_approved": true,
      "created_at": "2025-01-16T10:00:00Z",
      "updated_at": "2025-01-16T10:00:00Z"
    }
  ],
  "links": {
    "first": "http://localhost:8000/api/students?page=1",
    "last": "http://localhost:8000/api/students?page=1",
    "prev": null,
    "next": null
  },
  "meta": {
    "current_page": 1,
    "from": 1,
    "last_page": 1,
    "per_page": 15,
    "to": 1,
    "total": 1
  }
}
```

---

### 2. Create Student
**Endpoint:** `POST /api/students`

**Headers:** `Authorization: Bearer {token}`

**Required Permissions:** `students.manage` (Admin/Principal only)

**Request Body:**
```json
{
  "user_id": 10,
  "admission_number": "ADM001",
  "national_id": "12345678",
  "class_level_id": 1,
  "stream_id": 1,
  "date_of_birth": "2008-05-15",
  "gender": "male",
  "phone": "+254700001001",
  "admission_date": "2023-01-15"
}
```

**Success Response (201):**
```json
{
  "message": "Student created successfully",
  "data": { ... student object ... }
}
```

---

### 3. Get Student Details
**Endpoint:** `GET /api/students/{id}`

**Headers:** `Authorization: Bearer {token}`

**URL Parameters:**
- `id` - Student ID

**Success Response (200):**
```json
{
  "id": 1,
  "user_id": 10,
  "admission_number": "ADM001",
  "national_id": "12345678",
  "class_level_id": 1,
  "stream_id": 1,
  "date_of_birth": "2008-05-15",
  "gender": "male",
  "phone": "+254700001001",
  "admission_date": "2023-01-15",
  "status": "active",
  "is_approved": true,
  "user": {
    "id": 10,
    "name": "Jane Student",
    "email": "student@school.local"
  },
  "classLevel": {
    "id": 1,
    "name": "Form 1",
    "level": 1
  },
  "stream": {
    "id": 1,
    "name": "Stream A"
  }
}
```

---

### 4. Update Student
**Endpoint:** `PUT /api/students/{id}`

**Headers:** `Authorization: Bearer {token}`

**Request Body:** (Same fields as create)

**Success Response (200):**
```json
{
  "message": "Student updated successfully",
  "data": { ... updated student object ... }
}
```

---

### 5. Get Student Attendance History
**Endpoint:** `GET /api/students/{id}/attendance`

**Headers:** `Authorization: Bearer {token}`

**Query Parameters:**
- `month` - Filter by month (1-12)
- `year` - Filter by year
- `subject` - Filter by subject

**Success Response (200):**
```json
[
  {
    "id": 1,
    "student_id": 1,
    "date": "2026-01-16",
    "status": "present",
    "subject": "Mathematics",
    "recorded_by": 2,
    "remarks": null,
    "created_at": "2026-01-16T10:00:00Z"
  }
]
```

---

### 6. Get Student Fees
**Endpoint:** `GET /api/students/{id}/fees`

**Headers:** `Authorization: Bearer {token}`

**Success Response (200):**
```json
[
  {
    "id": 1,
    "student_id": 1,
    "term": "Term 1",
    "academic_year": 2025,
    "amount_due": 50000.00,
    "amount_paid": 30000.00,
    "balance": 20000.00,
    "status": "partial",
    "due_date": "2025-02-28",
    "paid_date": null
  }
]
```

---

### 7. Get Student Grades
**Endpoint:** `GET /api/students/{id}/grades`

**Headers:** `Authorization: Bearer {token}`

**Success Response (200):**
```json
[
  {
    "id": 1,
    "student_id": 1,
    "subject": "Mathematics",
    "term": "Term 1",
    "academic_year": 2025,
    "marks": 85,
    "grade": "A",
    "recorded_by": 2,
    "created_at": "2026-01-16T10:00:00Z"
  }
]
```

---

## Staff Management

### 1. List All Staff
**Endpoint:** `GET /api/staff`

**Headers:** `Authorization: Bearer {token}`

**Query Parameters:**
- `authority` - Filter by TSC or BOM
- `position` - Filter by position
- `status` - Filter by status (active, on_leave, retired, terminated)
- `search` - Search by name or staff number

**Success Response (200):**
```json
{
  "data": [
    {
      "id": 1,
      "user_id": 2,
      "staff_number": "TSC0001",
      "tsc_number": "TSC123456",
      "authority": "TSC",
      "position": "principal",
      "date_of_birth": "1975-03-20",
      "gender": "male",
      "phone": "+254700000001",
      "hire_date": "2010-01-15",
      "status": "active",
      "salary": 85000.00,
      "is_approved": true,
      "user": {
        "id": 2,
        "name": "Principal John Kamau",
        "email": "principal@elimucore.local"
      }
    }
  ]
}
```

---

### 2. Create Staff Member
**Endpoint:** `POST /api/staff`

**Headers:** `Authorization: Bearer {token}`

**Request Body:**
```json
{
  "user_id": 2,
  "staff_number": "TSC0001",
  "tsc_number": "TSC123456",
  "authority": "TSC",
  "position": "principal",
  "date_of_birth": "1975-03-20",
  "gender": "male",
  "phone": "+254700000001",
  "hire_date": "2010-01-15",
  "salary": 85000.00
}
```

---

### 3. Get Staff Payroll History
**Endpoint:** `GET /api/staff/{id}/payroll`

**Headers:** `Authorization: Bearer {token}`

**Query Parameters:**
- `year` - Filter by year
- `month` - Filter by month
- `status` - Filter by status (draft, approved, processed, paid)

**Success Response (200):**
```json
[
  {
    "id": 1,
    "staff_id": 1,
    "month": 1,
    "year": 2026,
    "basic_salary": 85000.00,
    "allowances": 5000.00,
    "deductions": 10000.00,
    "net_salary": 80000.00,
    "status": "paid",
    "approved_by": 1,
    "payment_date": "2026-01-31",
    "notes": "January payroll",
    "created_at": "2026-01-16T10:00:00Z"
  }
]
```

---

## Attendance

### 1. Record Attendance
**Endpoint:** `POST /api/attendance`

**Headers:** `Authorization: Bearer {token}`

**Required Permissions:** `attendance.record`

**Request Body:**
```json
{
  "student_id": 1,
  "date": "2026-01-16",
  "status": "present",
  "subject": "Mathematics",
  "recorded_by": 2,
  "remarks": ""
}
```

**Status Options:** `present`, `absent`, `excused`, `late`

**Success Response (201):**
```json
{
  "message": "Attendance recorded successfully",
  "data": {
    "id": 1,
    "student_id": 1,
    "date": "2026-01-16",
    "status": "present",
    "subject": "Mathematics",
    "recorded_by": 2,
    "remarks": null,
    "created_at": "2026-01-16T10:00:00Z"
  }
}
```

---

### 2. Record Bulk Attendance
**Endpoint:** `POST /api/attendance/bulk`

**Headers:** `Authorization: Bearer {token}`

**Request Body:**
```json
{
  "date": "2026-01-16",
  "records": [
    {
      "student_id": 1,
      "status": "present",
      "subject": "Mathematics"
    },
    {
      "student_id": 2,
      "status": "absent",
      "subject": "Mathematics",
      "remarks": "Medical appointment"
    }
  ]
}
```

**Success Response (201):**
```json
{
  "message": "Bulk attendance recorded successfully",
  "count": 2,
  "data": [ ... attendance records ... ]
}
```

---

### 3. Get Attendance Reports
**Endpoint:** `GET /api/attendance/reports`

**Headers:** `Authorization: Bearer {token}`

**Query Parameters:**
- `class_level_id` - Filter by class
- `stream_id` - Filter by stream
- `month` - Filter by month
- `year` - Filter by year
- `subject` - Filter by subject

**Success Response (200):**
```json
{
  "period": {
    "month": 1,
    "year": 2026
  },
  "statistics": {
    "total_records": 150,
    "present_count": 120,
    "absent_count": 20,
    "excused_count": 8,
    "late_count": 2,
    "attendance_rate": "80.00%"
  },
  "by_student": [ ... detailed records ... ]
}
```

---

## Fees & Payments

### 1. Create Fee Record
**Endpoint:** `POST /api/fees`

**Headers:** `Authorization: Bearer {token}`

**Required Permissions:** `fees.manage`

**Request Body:**
```json
{
  "student_id": 1,
  "term": "Term 1",
  "academic_year": 2025,
  "amount_due": 50000.00,
  "due_date": "2025-02-28"
}
```

**Success Response (201):**
```json
{
  "message": "Fee record created successfully",
  "data": {
    "id": 1,
    "student_id": 1,
    "term": "Term 1",
    "academic_year": 2025,
    "amount_due": 50000.00,
    "amount_paid": 0.00,
    "balance": 50000.00,
    "status": "pending",
    "due_date": "2025-02-28",
    "paid_date": null
  }
}
```

---

### 2. Record Payment
**Endpoint:** `POST /api/fees/{id}/record-payment`

**Headers:** `Authorization: Bearer {token}`

**Request Body:**
```json
{
  "amount_paid": 30000.00,
  "payment_date": "2026-01-16",
  "payment_method": "bank_transfer",
  "receipt_number": "REC001"
}
```

**Payment Methods:** `bank_transfer`, `cash`, `cheque`, `mpesa`

**Success Response (201):**
```json
{
  "message": "Payment recorded successfully",
  "data": {
    "id": 1,
    "fee_id": 1,
    "student_id": 1,
    "amount_paid": 30000.00,
    "payment_date": "2026-01-16",
    "payment_method": "bank_transfer",
    "receipt_number": "REC001",
    "status": "pending",
    "verified_by": null,
    "remarks": null,
    "created_at": "2026-01-16T10:00:00Z"
  }
}
```

---

### 3. Get Student Fees
**Endpoint:** `GET /api/fees/student/{student_id}`

**Headers:** `Authorization: Bearer {token}`

**Success Response (200):**
```json
[
  {
    "id": 1,
    "student_id": 1,
    "term": "Term 1",
    "academic_year": 2025,
    "amount_due": 50000.00,
    "amount_paid": 30000.00,
    "balance": 20000.00,
    "status": "partial",
    "due_date": "2025-02-28",
    "paid_date": null,
    "payments": [
      {
        "id": 1,
        "amount_paid": 30000.00,
        "payment_date": "2026-01-16",
        "payment_method": "bank_transfer",
        "status": "pending"
      }
    ]
  }
]
```

---

### 4. Get Arrears Report
**Endpoint:** `GET /api/fees/arrears`

**Headers:** `Authorization: Bearer {token}`

**Query Parameters:**
- `academic_year` - Filter by year
- `term` - Filter by term

**Success Response (200):**
```json
{
  "total_students": 50,
  "arrears_students": 8,
  "total_arrears": 245000.00,
  "records": [
    {
      "student_id": 1,
      "admission_number": "ADM001",
      "student_name": "Jane Student",
      "class_level": "Form 1",
      "total_balance": 45000.00,
      "fees": [ ... fee records with balances ... ]
    }
  ]
}
```

---

### 5. Get Collection Summary
**Endpoint:** `GET /api/fees/collection-summary`

**Headers:** `Authorization: Bearer {token}`

**Query Parameters:**
- `academic_year` - Filter by year
- `start_date` - Start date filter
- `end_date` - End date filter

**Success Response (200):**
```json
{
  "period": {
    "start_date": "2025-01-01",
    "end_date": "2026-01-16"
  },
  "summary": {
    "total_due": 2500000.00,
    "total_collected": 1875000.00,
    "total_balance": 625000.00,
    "collection_rate": "75.00%"
  },
  "by_term": [
    {
      "term": "Term 1",
      "total_due": 1000000.00,
      "total_collected": 850000.00,
      "balance": 150000.00,
      "collection_rate": "85.00%"
    }
  ],
  "payment_methods": [
    {
      "method": "bank_transfer",
      "count": 45,
      "amount": 900000.00
    },
    {
      "method": "cash",
      "count": 30,
      "amount": 600000.00
    }
  ]
}
```

---

## Payroll

### 1. Create Payroll Record
**Endpoint:** `POST /api/payroll`

**Headers:** `Authorization: Bearer {token}`

**Request Body:**
```json
{
  "staff_id": 1,
  "month": 1,
  "year": 2026,
  "basic_salary": 85000.00,
  "allowances": 5000.00,
  "deductions": 10000.00
}
```

**Success Response (201):**
```json
{
  "message": "Payroll record created successfully",
  "data": {
    "id": 1,
    "staff_id": 1,
    "month": 1,
    "year": 2026,
    "basic_salary": 85000.00,
    "allowances": 5000.00,
    "deductions": 10000.00,
    "net_salary": 80000.00,
    "status": "draft",
    "approved_by": null,
    "payment_date": null,
    "notes": null
  }
}
```

---

### 2. Approve Payroll
**Endpoint:** `POST /api/payroll/{id}/approve`

**Headers:** `Authorization: Bearer {token}`

**Required Permissions:** `payroll.manage` (Bursar only)

**Request Body:**
```json
{
  "notes": "January 2026 payroll approved"
}
```

**Success Response (200):**
```json
{
  "message": "Payroll approved successfully",
  "data": { ... updated payroll record ... }
}
```

---

### 3. Process Payment
**Endpoint:** `POST /api/payroll/{id}/pay`

**Headers:** `Authorization: Bearer {token}`

**Required Permissions:** `payroll.manage`

**Success Response (200):**
```json
{
  "message": "Payment processed successfully",
  "data": {
    "id": 1,
    "status": "paid",
    "payment_date": "2026-01-31"
  }
}
```

---

### 4. Bulk Generate Monthly Payroll
**Endpoint:** `POST /api/payroll/bulk-generate`

**Headers:** `Authorization: Bearer {token}`

**Request Body:**
```json
{
  "month": 1,
  "year": 2026
}
```

**Success Response (201):**
```json
{
  "message": "Payroll generated for 25 staff members",
  "count": 25,
  "total_amount": 2000000.00,
  "data": [ ... payroll records ... ]
}
```

---

## Reports

### 1. Generate Report
**Endpoint:** `POST /api/reports/generate`

**Headers:** `Authorization: Bearer {token}`

**Request Body:**
```json
{
  "type": "enrollment",
  "report_format": "pdf",
  "filters": {
    "academic_year": 2025,
    "class_level": 1
  }
}
```

**Report Types:** `enrollment`, `attendance`, `fees`, `payroll`, `academic`, `discipline`
**Formats:** `pdf`, `excel`, `csv`, `json`

---

### 2. Get Enrollment Report
**Endpoint:** `GET /api/reports/enrollment`

**Headers:** `Authorization: Bearer {token}`

**Success Response (200):**
```json
{
  "period": "2025",
  "total_students": 450,
  "by_class": {
    "Form 1": 120,
    "Form 2": 115,
    "Form 3": 110,
    "Form 4": 105
  },
  "by_stream": {
    "Stream A": 150,
    "Stream B": 150,
    "Stream C": 150
  },
  "status_breakdown": {
    "active": 440,
    "transferred": 5,
    "graduated": 5,
    "withdrawn": 0
  }
}
```

---

### 3. Get Attendance Report
**Endpoint:** `GET /api/reports/attendance`

**Headers:** `Authorization: Bearer {token}`

**Query Parameters:**
- `month` - Report month
- `year` - Report year
- `class_level_id` - Filter by class

---

### 4. Get Fees Report
**Endpoint:** `GET /api/reports/fees`

**Headers:** `Authorization: Bearer {token}`

---

### 5. Get Payroll Report
**Endpoint:** `GET /api/reports/payroll`

**Headers:** `Authorization: Bearer {token}`

**Query Parameters:**
- `month` - Report month
- `year` - Report year

---

## Approvals

### 1. List Approval Requests
**Endpoint:** `GET /api/approvals`

**Headers:** `Authorization: Bearer {token}`

**Required Permissions:** `approvals.review`

**Query Parameters:**
- `status` - Filter by status (pending, approved, rejected)

---

### 2. Get Pending Approvals
**Endpoint:** `GET /api/approvals/pending`

**Headers:** `Authorization: Bearer {token}`

**Success Response (200):**
```json
[
  {
    "id": 1,
    "user_id": 5,
    "approvable_type": "User",
    "approvable_id": 5,
    "status": "pending",
    "reviewed_by": null,
    "review_remarks": null,
    "reviewed_at": null,
    "user": {
      "id": 5,
      "name": "John Doe",
      "email": "john@school.local",
      "role": "teacher"
    }
  }
]
```

---

### 3. Approve Request
**Endpoint:** `POST /api/approvals/{id}/approve`

**Headers:** `Authorization: Bearer {token}`

**Request Body:**
```json
{
  "remarks": "Application reviewed and approved"
}
```

**Success Response (200):**
```json
{
  "message": "Application approved successfully",
  "data": {
    "id": 1,
    "status": "approved",
    "reviewed_by": 1,
    "review_remarks": "Application reviewed and approved",
    "reviewed_at": "2026-01-16T10:00:00Z"
  }
}
```

---

### 4. Reject Request
**Endpoint:** `POST /api/approvals/{id}/reject`

**Headers:** `Authorization: Bearer {token}`

**Request Body:**
```json
{
  "remarks": "Incomplete application. Please resubmit with required documents."
}
```

---

## Error Handling

### Common Error Responses

**401 Unauthorized (Missing/Invalid Token):**
```json
{
  "message": "Unauthenticated.",
  "errors": {}
}
```

**403 Forbidden (Insufficient Permissions):**
```json
{
  "message": "This action is unauthorized.",
  "errors": {}
}
```

**404 Not Found:**
```json
{
  "message": "Resource not found",
  "errors": {}
}
```

**422 Validation Error:**
```json
{
  "message": "The given data was invalid.",
  "errors": {
    "field_name": ["Error message here"],
    "another_field": ["Multiple", "errors"]
  }
}
```

**500 Server Error:**
```json
{
  "message": "Server error. Please try again later.",
  "errors": {}
}
```

---

## Authentication Headers

All endpoints (except `/api/auth/register` and `/api/auth/login`) require:

```
Authorization: Bearer {access_token}
Content-Type: application/json
```

---

## Rate Limiting

API endpoints are rate limited to prevent abuse:
- 60 requests per minute per IP address
- 1000 requests per hour per user

---

## Timestamps

All timestamps are in UTC (ISO 8601 format):
```
2026-01-16T10:00:00Z
```

---

## Data Types

- **ID**: Integer (positive)
- **UUID**: 36-character string (if applicable)
- **Date**: YYYY-MM-DD format
- **DateTime**: ISO 8601 format
- **Decimal**: String with 2 decimal places (e.g., "85000.00")
- **Boolean**: true/false

---

**API Version:** 1.0.0  
**Last Updated:** January 16, 2026  
**Status:** Stable ✅

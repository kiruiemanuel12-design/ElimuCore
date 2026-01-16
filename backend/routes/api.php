<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\StaffController;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\FeeController;
use App\Http\Controllers\PayrollController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\ApprovalController;

Route::post('/auth/register', [AuthController::class, 'register']);
Route::post('/auth/login', [AuthController::class, 'login']);
Route::post('/auth/logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');

Route::middleware('auth:sanctum')->group(function () {
    // User Management
    Route::get('/user', [AuthController::class, 'getUser']);
    Route::put('/user', [AuthController::class, 'updateProfile']);
    Route::post('/user/change-password', [AuthController::class, 'changePassword']);

    // Students
    Route::apiResource('students', StudentController::class);
    Route::get('/students/{student}/attendance', [StudentController::class, 'attendanceHistory']);
    Route::get('/students/{student}/fees', [StudentController::class, 'fees']);
    Route::get('/students/{student}/grades', [StudentController::class, 'grades']);
    Route::get('/students/{student}/guardians', [StudentController::class, 'guardians']);

    // Staff
    Route::apiResource('staff', StaffController::class);
    Route::get('/staff/{staff}/payroll', [StaffController::class, 'payrollHistory']);
    Route::post('/staff/{staff}/payroll', [StaffController::class, 'generatePayroll']);

    // Attendance
    Route::apiResource('attendance', AttendanceController::class);
    Route::get('/attendance/class/{classLevel}', [AttendanceController::class, 'byClass']);
    Route::get('/attendance/student/{student}', [AttendanceController::class, 'byStudent']);
    Route::post('/attendance/bulk', [AttendanceController::class, 'recordBulk']);
    Route::get('/attendance/reports', [AttendanceController::class, 'report']);

    // Fees & Payments
    Route::apiResource('fees', FeeController::class);
    Route::post('/fees/{fee}/record-payment', [FeeController::class, 'recordPayment']);
    Route::get('/fees/student/{student}', [FeeController::class, 'studentFees']);
    Route::get('/fees/arrears', [FeeController::class, 'arrears']);
    Route::get('/fees/collection-summary', [FeeController::class, 'collectionSummary']);

    // Payroll
    Route::apiResource('payroll', PayrollController::class);
    Route::post('/payroll/{payroll}/approve', [PayrollController::class, 'approve'])->middleware('can:approve-payroll');
    Route::post('/payroll/{payroll}/pay', [PayrollController::class, 'processPay'])->middleware('can:process-payroll');
    Route::post('/payroll/bulk-generate', [PayrollController::class, 'bulkGenerate']);
    Route::get('/payroll/month/{month}/{year}', [PayrollController::class, 'byMonth']);

    // Reports
    Route::get('/reports', [ReportController::class, 'index']);
    Route::post('/reports/generate', [ReportController::class, 'generate']);
    Route::get('/reports/enrollment', [ReportController::class, 'enrollmentReport']);
    Route::get('/reports/attendance', [ReportController::class, 'attendanceReport']);
    Route::get('/reports/fees', [ReportController::class, 'feesReport']);
    Route::get('/reports/payroll', [ReportController::class, 'payrollReport']);
    Route::get('/reports/academic', [ReportController::class, 'academicReport']);

    // Approvals
    Route::get('/approvals', [ApprovalController::class, 'index'])->middleware('can:review-approvals');
    Route::post('/approvals/{approval}/approve', [ApprovalController::class, 'approve'])->middleware('can:review-approvals');
    Route::post('/approvals/{approval}/reject', [ApprovalController::class, 'reject'])->middleware('can:review-approvals');
    Route::get('/approvals/pending', [ApprovalController::class, 'pending'])->middleware('can:review-approvals');
});

// Health check
Route::get('/health', function () {
    return response()->json(['status' => 'ok', 'timestamp' => now()]);
});

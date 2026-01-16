<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasFactory, Notifiable, HasApiTokens;

    protected $fillable = [
        'name', 'email', 'phone', 'password', 'role', 'is_approved',
        'approved_at', 'approved_by', 'approval_reason', 'employee_id',
        'staff_number', 'last_login_at', 'is_active'
    ];

    protected $hidden = ['password', 'remember_token'];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'approved_at' => 'datetime',
            'last_login_at' => 'datetime',
            'password' => 'hashed',
            'is_approved' => 'boolean',
            'is_active' => 'boolean',
        ];
    }

    // Relationships
    public function student(): HasOne
    {
        return $this->hasOne(Student::class);
    }

    public function staff(): HasOne
    {
        return $this->hasOne(Staff::class);
    }

    public function attendances(): HasMany
    {
        return $this->hasMany(Attendance::class, 'recorded_by');
    }

    public function grades(): HasMany
    {
        return $this->hasMany(Grade::class, 'recorded_by');
    }

    public function auditLogs(): HasMany
    {
        return $this->hasMany(AuditLog::class);
    }

    public function approvals(): HasMany
    {
        return $this->hasMany(Approval::class, 'reviewed_by');
    }

    public function feePaymentsVerified(): HasMany
    {
        return $this->hasMany(FeePayment::class, 'verified_by');
    }

    public function payrollsApproved(): HasMany
    {
        return $this->hasMany(Payroll::class, 'approved_by');
    }

    public function reportsGenerated(): HasMany
    {
        return $this->hasMany(Report::class, 'generated_by');
    }

    // Scopes
    public function scopeApproved($query)
    {
        return $query->where('is_approved', true);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeByRole($query, string $role)
    {
        return $query->where('role', $role);
    }

    // Helpers
    public function hasPermission(string $permission): bool
    {
        $rolePermissions = config('permissions.' . $this->role, []);
        return in_array($permission, $rolePermissions);
    }

    public function canApproveUsers(): bool
    {
        return in_array($this->role, ['super_admin', 'principal']);
    }

    public function canManageFinances(): bool
    {
        return in_array($this->role, ['super_admin', 'bursar']);
    }

    public function canRecordAttendance(): bool
    {
        return in_array($this->role, ['super_admin', 'principal', 'deputy_academic', 'teacher']);
    }
}


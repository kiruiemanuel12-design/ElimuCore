<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Staff extends Model
{
    protected $table = 'staff';

    protected $fillable = [
        'user_id', 'staff_number', 'tsc_number', 'authority', 'position',
        'date_of_birth', 'gender', 'phone', 'hire_date', 'status', 'salary',
        'is_approved'
    ];

    protected $casts = [
        'date_of_birth' => 'date',
        'hire_date' => 'date',
        'salary' => 'decimal:2',
        'is_approved' => 'boolean',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function payrolls(): HasMany
    {
        return $this->hasMany(Payroll::class);
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopeApproved($query)
    {
        return $query->where('is_approved', true);
    }

    public function scopeByAuthority($query, $authority)
    {
        return $query->where('authority', $authority);
    }

    // Methods
    public function isTSC()
    {
        return $this->authority === 'TSC';
    }

    public function isBOM()
    {
        return $this->authority === 'BOM';
    }

    public function getLatestPayroll()
    {
        return $this->payrolls()->latest()->first();
    }

    public function getTotalEarnings($year)
    {
        return $this->payrolls()
            ->where('year', $year)
            ->where('status', 'paid')
            ->sum('net_salary');
    }
}


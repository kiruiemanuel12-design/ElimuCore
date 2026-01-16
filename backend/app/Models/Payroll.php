<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Payroll extends Model
{
    protected $fillable = [
        'staff_id', 'month', 'year', 'basic_salary', 'allowances',
        'deductions', 'net_salary', 'status', 'approved_by', 'payment_date', 'notes'
    ];

    protected $casts = [
        'basic_salary' => 'decimal:2',
        'allowances' => 'decimal:2',
        'deductions' => 'decimal:2',
        'net_salary' => 'decimal:2',
        'payment_date' => 'date',
    ];

    public function staff(): BelongsTo
    {
        return $this->belongsTo(Staff::class);
    }

    public function approvedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    // Scopes
    public function scopeDraft($query)
    {
        return $query->where('status', 'draft');
    }

    public function scopeApproved($query)
    {
        return $query->where('status', 'approved');
    }

    public function scopePaid($query)
    {
        return $query->where('status', 'paid');
    }

    public function scopeByMonth($query, $month, $year)
    {
        return $query->where('month', $month)->where('year', $year);
    }

    // Methods
    public function calculateNetSalary()
    {
        $this->net_salary = $this->basic_salary + $this->allowances - $this->deductions;
        return $this;
    }

    public function approve($userId, $notes = null)
    {
        $this->status = 'approved';
        $this->approved_by = $userId;
        if ($notes) $this->notes = $notes;
        return $this->save();
    }

    public function markAsPaid()
    {
        $this->status = 'paid';
        $this->payment_date = now();
        return $this->save();
    }
}


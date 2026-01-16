<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Fee extends Model
{
    protected $fillable = [
        'student_id', 'term', 'academic_year', 'amount_due', 'amount_paid',
        'balance', 'status', 'due_date', 'paid_date'
    ];

    protected $casts = [
        'amount_due' => 'decimal:2',
        'amount_paid' => 'decimal:2',
        'balance' => 'decimal:2',
        'due_date' => 'date',
        'paid_date' => 'date',
    ];

    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }

    public function payments(): HasMany
    {
        return $this->hasMany(FeePayment::class);
    }

    // Scopes
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeArrear($query)
    {
        return $query->where('status', 'arrear');
    }

    public function scopePaid($query)
    {
        return $query->where('status', 'paid');
    }

    public function scopeByAcademicYear($query, $year)
    {
        return $query->where('academic_year', $year);
    }

    // Methods
    public function isOverdue()
    {
        return $this->status !== 'paid' && $this->due_date < now();
    }

    public function markAsPaid()
    {
        $this->status = 'paid';
        $this->amount_paid = $this->amount_due;
        $this->balance = 0;
        $this->paid_date = now();
        return $this->save();
    }
}


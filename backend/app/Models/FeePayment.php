<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class FeePayment extends Model
{
    protected $fillable = [
        'fee_id', 'student_id', 'amount_paid', 'payment_date', 'payment_method',
        'receipt_number', 'status', 'verified_by', 'remarks'
    ];

    protected $casts = [
        'amount_paid' => 'decimal:2',
        'payment_date' => 'date',
    ];

    public function fee(): BelongsTo
    {
        return $this->belongsTo(Fee::class);
    }

    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }

    public function verifiedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'verified_by');
    }

    // Scopes
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeVerified($query)
    {
        return $query->where('status', 'verified');
    }

    public function scopeByPaymentMethod($query, $method)
    {
        return $query->where('payment_method', $method);
    }

    // Methods
    public function verify($userId, $remarks = null)
    {
        $this->status = 'verified';
        $this->verified_by = $userId;
        $this->remarks = $remarks;
        return $this->save();
    }

    public function reject()
    {
        $this->status = 'rejected';
        return $this->save();
    }
}


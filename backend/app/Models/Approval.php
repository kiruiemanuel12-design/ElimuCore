<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Approval extends Model
{
    protected $fillable = [
        'user_id', 'approvable_type', 'approvable_id', 'status',
        'reviewed_by', 'review_remarks', 'reviewed_at'
    ];

    protected $casts = [
        'reviewed_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function reviewedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reviewed_by');
    }

    // Scopes
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeApproved($query)
    {
        return $query->where('status', 'approved');
    }

    public function scopeRejected($query)
    {
        return $query->where('status', 'rejected');
    }

    // Methods
    public function approve($userId, $remarks = null)
    {
        $this->status = 'approved';
        $this->reviewed_by = $userId;
        $this->review_remarks = $remarks;
        $this->reviewed_at = now();
        return $this->save();
    }

    public function reject($userId, $remarks)
    {
        $this->status = 'rejected';
        $this->reviewed_by = $userId;
        $this->review_remarks = $remarks;
        $this->reviewed_at = now();
        return $this->save();
    }
}


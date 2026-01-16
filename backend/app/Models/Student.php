<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Student extends Model
{
    protected $fillable = [
        'user_id', 'admission_number', 'national_id', 'class_level_id',
        'stream_id', 'date_of_birth', 'gender', 'phone', 'admission_date',
        'status', 'is_approved'
    ];

    protected $casts = [
        'date_of_birth' => 'date',
        'admission_date' => 'date',
        'is_approved' => 'boolean',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function classLevel(): BelongsTo
    {
        return $this->belongsTo(ClassLevel::class);
    }

    public function stream(): BelongsTo
    {
        return $this->belongsTo(Stream::class);
    }

    public function attendances(): HasMany
    {
        return $this->hasMany(Attendance::class);
    }

    public function fees(): HasMany
    {
        return $this->hasMany(Fee::class);
    }

    public function feePayments(): HasMany
    {
        return $this->hasMany(FeePayment::class);
    }

    public function guardians(): HasMany
    {
        return $this->hasMany(Guardian::class);
    }

    public function grades(): HasMany
    {
        return $this->hasMany(Grade::class);
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

    // Methods
    public function getAttendancePercentage($term = null)
    {
        $query = $this->attendances()->where('status', 'present');
        if ($term) {
            $query->where('term', $term);
        }
        $present = $query->count();
        $total = $this->attendances()->count();
        return $total > 0 ? ($present / $total) * 100 : 0;
    }

    public function getFeeBalance()
    {
        return $this->fees()->sum('balance');
    }

    public function getPrimaryGuardian()
    {
        return $this->guardians()->where('contact_priority', 'primary')->first();
    }
}


<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Report extends Model
{
    protected $fillable = [
        'name', 'description', 'type', 'report_format', 'filters',
        'generated_by', 'file_path', 'generated_at'
    ];

    protected $casts = [
        'filters' => 'array',
        'generated_at' => 'datetime',
    ];

    public function generatedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'generated_by');
    }

    // Scopes
    public function scopeByType($query, $type)
    {
        return $query->where('type', $type);
    }

    public function scopeByFormat($query, $format)
    {
        return $query->where('report_format', $format);
    }

    public function scopeRecent($query)
    {
        return $query->latest('generated_at');
    }
}


<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ClassLevel extends Model
{
    protected $fillable = ['name', 'level', 'description'];

    public function students(): HasMany
    {
        return $this->hasMany(Student::class);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('level');
    }
}


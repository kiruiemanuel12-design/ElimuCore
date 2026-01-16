<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Guardian extends Model
{
    protected $fillable = [
        'student_id', 'name', 'relationship', 'phone', 'email', 'id_number',
        'occupation', 'address', 'contact_priority'
    ];

    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }

    // Scopes
    public function scopePrimary($query)
    {
        return $query->where('contact_priority', 'primary');
    }

    public function scopeSecondary($query)
    {
        return $query->where('contact_priority', 'secondary');
    }

    public function scopeEmergency($query)
    {
        return $query->where('contact_priority', 'emergency');
    }

    // Methods
    public function setPrimary()
    {
        $this->student->guardians()->update(['contact_priority' => 'secondary']);
        $this->contact_priority = 'primary';
        return $this->save();
    }
}


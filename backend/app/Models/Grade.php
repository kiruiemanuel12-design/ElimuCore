<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Grade extends Model
{
    protected $fillable = [
        'student_id', 'subject', 'term', 'academic_year', 'marks',
        'grade', 'recorded_by'
    ];

    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }

    public function recordedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'recorded_by');
    }

    // Scopes
    public function scopeBySubject($query, $subject)
    {
        return $query->where('subject', $subject);
    }

    public function scopeByTerm($query, $term)
    {
        return $query->where('term', $term);
    }

    public function scopeByYear($query, $year)
    {
        return $query->where('academic_year', $year);
    }

    // Methods
    public function assignGrade()
    {
        if (!$this->marks) return;

        $grades = [
            'A' => 80, 'B+' => 75, 'B' => 70, 'B-' => 65,
            'C+' => 60, 'C' => 55, 'C-' => 50, 'D+' => 45,
            'D' => 40, 'E' => 0
        ];

        foreach ($grades as $grade => $minMark) {
            if ($this->marks >= $minMark) {
                $this->grade = $grade;
                break;
            }
        }
    }

    public function getGradePoint()
    {
        $points = [
            'A' => 4.0, 'B+' => 3.5, 'B' => 3.0, 'B-' => 2.5,
            'C+' => 2.0, 'C' => 1.5, 'C-' => 1.0, 'D+' => 0.5,
            'D' => 0.25, 'E' => 0
        ];
        return $points[$this->grade] ?? 0;
    }
}


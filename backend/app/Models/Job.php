<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Job extends Model
{
    // الحقول القابلة للتعبئة
    protected $fillable = [
        'employer_id',
        'title',
        'description',
        'responsibilities',
        'skills',
        'qualifications',
        'salary_min',
        'salary_max',
        'benefits',
        'location',
        'work_type',
        'application_deadline',
        'status',
    ];

    // تحويل حقل skills إلى array تلقائيًا
    protected $casts = [
        'skills' => 'array',
        'application_deadline' => 'date',
    ];

    // العلاقة مع Employer
    public function employer(): BelongsTo
    {
        return $this->belongsTo(Employer::class);
    }
}
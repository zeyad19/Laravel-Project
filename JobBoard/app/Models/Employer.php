<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Employer extends Model
{
    // الحقول القابلة للتعبئة
    protected $fillable = [
        'user_id',
        'company_name',
        'company_website',
        'logo',
        'location',
    ];

    // العلاقة مع User
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
    public function candidate()
{
    return $this->belongsTo(Candidate::class);
}

    // العلاقة مع Jobs
    public function jobs(): HasMany
    {
        return $this->hasMany(Job::class);
    }
    public function Application(): HasMany
    {
        return $this->hasMany(Application::class);
    }

}
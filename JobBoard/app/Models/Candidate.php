<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Candidate extends Model
{
    protected $fillable = [
        'user_id',
        'phone_number',
        'cv',
        'experience_level',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}

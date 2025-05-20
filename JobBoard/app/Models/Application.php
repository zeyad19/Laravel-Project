<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Application extends Model
{
    //
    protected $fillable = [
        'resume_file',
        'job_id',
        'candidate_id',
        'status',
        'contact_info',
    ];
public function job()
{
    return $this->belongsTo(Job::class);
}

// In the Application model
public function candidate()
{
    return $this->belongsTo(Candidate::class);
}

}

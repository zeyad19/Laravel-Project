<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ApplicationRequest extends FormRequest
{
   public function rules(): array
{
    if ($this->isMethod('PUT') || $this->isMethod('PATCH')) {
        return [
            'job_id' => 'required|exists:jobs,id',
            'candidate_id' => 'required|exists:candidates,id',
            'contact_info' => 'required|string',
            'resume' => 'sometimes|file|mimes:pdf,doc,docx',
            'status' => 'nullable|in:pending,accepted,rejected',
        ];
    }

    return [
        'job_id' => 'required|exists:jobs,id',
        'candidate_id' => 'required|exists:candidates,id',
        'contact_info' => 'required|string',
        'resume' => 'required|file|mimes:pdf,doc,docx',
        'status' => 'nullable|in:pending,accepted,rejected',
    ];
}

}

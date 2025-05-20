<?php

namespace App\Http\Controllers;

use App\Models\Employer;
use App\Models\Application;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\JsonResponse;


class EmployerApplicationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
// public function index()
// {

//     $employerId =1;

//     $applications = Application::with(['job', 'candidate'])
//         ->whereHas('job', function ($query) use ($employerId) {
//             $query->where('employer_id', $employerId);
//         })
//         ->get(['id as application_id', 'status', 'job_id', 'candidate_id']);

//     $formattedApplications = $applications->map(function ($application) {
//         return [
//             'application_id' => $application->application_id,
//             'status' => $application->status,
//             'job_title' => $application->job->title,
//             'job_id' => $application->job->id,
//             'candidate_name' => $application->candidate ? $application->candidate->full_name : null,
//             'candidate_email' => $application->candidate ? $application->candidate->email : null,
//         ];
//     });

//     return response()->json([
//         'applications' => $formattedApplications,
//         'count' => $formattedApplications->count()
//     ]);
// }
 public function index(): JsonResponse
    {
        // $employerId = Auth::id();  
        $employerId = 1; 
         
        $applications = DB::table('applications as a')
            ->join('jobs as j', 'a.job_id', '=', 'j.id')
            ->leftJoin('candidates as c', 'a.candidate_id', '=', 'c.id') 
            ->leftJoin('users as u', 'c.user_id', '=', 'u.id')  
            ->where('j.employer_id', $employerId)
            ->select(
                'a.id as application_id', 
                'a.status', 
                'j.title as job_title', 
                'j.id as job_id', 
                'u.name as candidate_name', 
                'u.email as candidate_email'  
            )
            ->get();

        return response()->json([
            'applications' => $applications,
            'count' => $applications->count()
        ]);
    }
    


    /**
     * Display the specified resource.
     */
public function show(string $id)
{
    $application = DB::table('applications as a')
        ->join('jobs as j', 'a.job_id', '=', 'j.id')
        ->join('candidates as c', 'a.candidate_id', '=', 'c.id')
        ->join('users as u', 'c.user_id', '=', 'u.id')
        ->where('a.id', $id)
        ->select(
            'a.id as application_id',
            'a.status',
            'a.resume_file',
            'a.contact_info',
            'a.created_at as applied_at',
            'j.title as job_title',
            'u.name as candidate_name',
            'u.email as candidate_email',
            'c.phone_number',
            'c.experience_level'
        )
        ->first();

    if (!$application) {
        return response()->json(['error' => 'Application not found'], 404);
    }

    return response()->json(['application' => $application]);
}


  
    /**
     * Update the specified resource in storage.
     */
public function updatestatus(Request $request, string $id)
{
    $validStatuses = ['pending', 'accepted', 'rejected'];

    $status = $request->input('status');
    if (!in_array($status, $validStatuses)) {
        return response()->json(['error' => 'Invalid status provided.'], 400);
    }

    $application = Application::find($id);

    if ($application) {
        $application->status = $status;
        $application->save();

        return response()->json(['message' => 'Application status updated successfully.']);
    }

    return response()->json(['error' => 'Application not found.'], 404);
}


    /**
     * Remove the specified resource from storage.
     */
  
}

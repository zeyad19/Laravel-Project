<?php

namespace App\Http\Controllers;

use App\Http\Requests\ApplicationRequest;
use Illuminate\Http\Request;
use App\Models\Application;
use Illuminate\Http\JsonResponse;
use App\Models\Candidate;
use App\Models\Job;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class CandidateApplicationController extends Controller
{
    /**
     * Display a listing of the candidate's applications.
     */
    public function index(): JsonResponse
    {
        $candidateId = request()->input('candidate_id'); //

        if (!$candidateId || !Candidate::find($candidateId)) {
            return response()->json(['error' => 'Invalid candidate ID.'], 403);
        }

        // Fetch only the candidate's applications with job details
        $applications = Application::where('candidate_id', $candidateId)
            ->with(['job' => function ($query) {
                $query->select('id', 'title', 'company_name'); 
            }])
            ->get(['id', 'job_id', 'candidate_id', 'resume_file', 'contact_info', 'status', 'created_at']);

        return response()->json(['applications' => $applications], 200);
    }

    /**
     * Store a newly created application in storage.
     */
    public function store(ApplicationRequest $request): JsonResponse
    {
        $candidateId = $request->input('candidate_id');
        if (!$candidateId || !Candidate::find($candidateId)) {
            return response()->json(['error' => 'Invalid candidate ID.'], 403);
        }

        // Check if the job exists and available
        $job = Job::find($request->input('job_id'));
        if (!$job) {
            return response()->json(['error' => 'Job not found.'], 404);
        }
        if ($job->status !== 'approved' || $job->application_deadline < now()) {
            return response()->json(['error' => 'The job is not available or the application deadline has passed.'], 403);
        }

        // Check if the candidate has already applied for this job
        $existingApplication = Application::where('job_id', $request->input('job_id'))
            ->where('candidate_id', $candidateId)
            ->first();
        if ($existingApplication) {
            return response()->json(['error' => 'You have already applied for this job.'], 422);
        }

        // Process the resume file
        try {
            if (!$request->hasFile('resume') || !$request->file('resume')->isValid()) {
                return response()->json(['error' => 'Resume must be a valid PDF or Word file.'], 422);
            }

            // Store the resume with a unique name
            $path = $request->file('resume')->store('resumes', 'public');

            // Create the application
            $application = new Application();
            $application->job_id = $request->input('job_id');
            $application->candidate_id = $candidateId;
            $application->resume_file = $path;
            $application->contact_info = $request->input('contact_info'); // JSON validated in ApplicationRequest
            $application->status = 'pending';
            $application->save();

            return response()->json([
                'message' => 'Application submitted successfully.',
                'application' => $application
            ], 201);
        } catch (\Exception $e) {
            Log::error('Failed to submit application: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to submit application. Please try again.'], 500);
        }
    }

    /**
     * Display the specified application.
     */
    public function show(string $id): JsonResponse
    {
        $application = Application::with(['job' => function ($query) {
            $query->select('id', 'title', 'company_name');
        }])->find($id);

        if (!$application) {
            return response()->json(['error' => 'Application not found.'], 404);
        }

        $candidateId = request()->input('candidate_id');
        if ($application->candidate_id != $candidateId) {
            return response()->json(['error' => 'Unauthorized access to this application.'], 403);
        }

        return response()->json(['application' => $application], 200);
    }

    /**
     * Update the specified application in storage.
     */
    public function update(ApplicationRequest $request, string $id): JsonResponse
    {
        // Find the application
        $application = Application::find($id);
        if (!$application) {
            return response()->json(['error' => 'Application not found.'], 404);
        }

        $candidateId = $request->input('candidate_id');
        if ($application->candidate_id != $candidateId) {
            return response()->json(['error' => 'Unauthorized access to this application.'], 403);
        }

        // Check if the application status allows updates
        if ($application->status !== 'pending') {
            return response()->json(['error' => 'Cannot update an application in its current state.'], 403);
        }

        // Check if the new job is valid
        $job = Job::find($request->input('job_id'));
        if (!$job) {
            return response()->json(['error' => 'Job not found.'], 404);
        }
        if ($job->status !== 'approved' || $job->application_deadline < now()) {
            return response()->json(['error' => 'The job is not available or the application deadline has passed.'], 403);
        }

        // Check for duplicate application if job_id is changed
        if ($application->job_id != $request->input('job_id')) {
            $existingApplication = Application::where('job_id', $request->input('job_id'))
                ->where('candidate_id', $candidateId)
                ->where('id', '!=', $id)
                ->first();
            if ($existingApplication) {
                return response()->json(['error' => 'You have already applied for this job.'], 422);
            }
        }

        $path = $application->resume_file;
        if ($request->hasFile('resume') && $request->file('resume')->isValid()) {
            // Delete old resume if it exists
            if ($path && Storage::disk('public')->exists($path)) {
                Storage::disk('public')->delete($path);
            }
            $path = $request->file('resume')->store('resumes', 'public');
        }

        // Update the application
        try {
            $application->job_id = $request->input('job_id');
            $application->candidate_id = $candidateId;
            $application->resume_file = $path;
            $application->contact_info = $request->input('contact_info');
            $application->status = 'pending';
            $application->save();

            return response()->json(['message' => 'Application updated successfully.', 'application' => $application], 200);
        } catch (\Exception $e) {
            Log::error('Failed to update application: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to update application. Please try again.'], 500);
        }
    }

    /**
     * Cancel the specified application.
     */
    public function destroy(string $id): JsonResponse
    {
        // Find the application
        $application = Application::find($id);
        if (!$application) {
            return response()->json(['error' => 'Application not found.'], 404);
        }

        $candidateId = request()->input('candidate_id');
        if ($application->candidate_id != $candidateId) {
            return response()->json(['error' => 'Unauthorized access to this application.'], 403);
        }

        // Check if the application can be canceled
        if ($application->status !== 'pending') {
            return response()->json(['error' => 'Cannot cancel an application in its current state.'], 403);
        }

        try {
            // Update status to canceled instead of deleting
            $application->status = 'canceled';
            $application->save();

            return response()->json(['message' => 'Application canceled successfully.'], 200);
        } catch (\Exception $e) {
            Log::error('Failed to cancel application: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to cancel application. Please try again.'], 500);
        }
    }
}
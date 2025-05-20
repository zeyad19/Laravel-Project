<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CandidateApplicationController;
use App\Http\Controllers\EmployerApplicationController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');
Route::resource('/application',CandidateApplicationController::class);
// Route::prefix('candidate/applications')->group(function () {
//     Route::get('/', [CandidateApplicationController::class, 'index']);          
//     Route::post('/', [CandidateApplicationController::class, 'store']);           
//     Route::get('{id}', [CandidateApplicationController::class, 'show']);           
//     Route::put('{id}', [CandidateApplicationController::class, 'update']);       
//     Route::delete('{id}', [CandidateApplicationController::class, 'destroy']); 
// });
Route::get('/employer/{employer_id}/applications', [EmployerApplicationController::class, 'index']);
Route::put('applications/{id}/status', [EmployerApplicationController::class, 'updatestatus']);
Route::get('/applications/{id}', [EmployerApplicationController::class, 'show']);

<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Employer;
use App\Models\Job;
use App\Models\Candidate;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // إضافة أو جلب سجل في users للـ employer
        $user1 = User::firstOrCreate(
            ['email' => 'employer@example.com'],
            [
                'name' => 'Test Employer',
                'password' => bcrypt('password123'),
            ]
        );

        // إضافة أو جلب سجل في users للـ candidate
        $user2 = User::firstOrCreate(
            ['email' => 'candidate@example.com'],
            [
                'name' => 'Test Candidate',
                'password' => bcrypt('password123'),
            ]
        );

        // إضافة سجل في employers
        $employer = Employer::firstOrCreate(
            ['user_id' => $user1->id],
            [
                'company_name' => 'Test Company',
                'location' => 'Cairo',
            ]
        );

        // إضافة سجل في jobs
        Job::firstOrCreate(
            ['title' => 'Test Job', 'employer_id' => $employer->id],
            [
                'description' => 'Test Description',
                'skills' => json_encode(['PHP', 'Laravel']),
                'location' => 'Cairo',
                'work_type' => 'remote',
                'application_deadline' => '2025-12-31',
            ]
        );

        // إضافة سجل في candidates
        Candidate::firstOrCreate(
            ['user_id' => $user2->id],
            [
                'phone_number' => '1234567890',
                'experience_level' => 'Mid-level',
            ]
        );
    }
}
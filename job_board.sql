-- إنشاء الداتابيز (مع التأكد إنها مش موجودة عشان ما يحصلش خطأ)
DROP DATABASE IF EXISTS job_board;
CREATE DATABASE job_board;
USE job_board;

-- إنشاء جدول users
CREATE TABLE users (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL UNIQUE,
    email_verified_at TIMESTAMP NULL DEFAULT NULL,
    password VARCHAR(255) NOT NULL,
    role VARCHAR(255) NOT NULL COMMENT 'candidate, employer, admin',
    is_suspended TINYINT(1) NOT NULL DEFAULT 0,
    remember_token VARCHAR(100) NULL DEFAULT NULL,
    created_at TIMESTAMP NULL DEFAULT NULL,
    updated_at TIMESTAMP NULL DEFAULT NULL
);

-- إنشاء جدول personal_access_tokens (بتاع Sanctum)
CREATE TABLE personal_access_tokens (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    tokenable_type VARCHAR(255) NOT NULL,
    tokenable_id BIGINT UNSIGNED NOT NULL,
    name VARCHAR(255) NOT NULL,
    token VARCHAR(64) NOT NULL UNIQUE,
    abilities TEXT NULL DEFAULT NULL,
    last_used_at TIMESTAMP NULL DEFAULT NULL,
    expires_at TIMESTAMP NULL DEFAULT NULL,
    created_at TIMESTAMP NULL DEFAULT NULL,
    updated_at TIMESTAMP NULL DEFAULT NULL,
    INDEX personal_access_tokens_tokenable_type_tokenable_id_index (tokenable_type, tokenable_id)
);

-- إنشاء جدول jobs
CREATE TABLE jobs (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    description TEXT NOT NULL,
    salary DECIMAL(10, 2) NOT NULL,
    location VARCHAR(255) NOT NULL,
    company VARCHAR(255) NOT NULL,
    experience VARCHAR(255) NOT NULL COMMENT 'entry, mid, senior',
    remote TINYINT(1) NOT NULL DEFAULT 0,
    employer_id BIGINT UNSIGNED NOT NULL,
    posted_date TIMESTAMP NOT NULL,
    status ENUM('pending', 'approved', 'rejected') NOT NULL DEFAULT 'pending',
    created_at TIMESTAMP NULL DEFAULT NULL,
    updated_at TIMESTAMP NULL DEFAULT NULL,
    FOREIGN KEY (employer_id) REFERENCES users(id) ON DELETE CASCADE
);

-- إنشاء جدول applications
CREATE TABLE applications (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    job_id BIGINT UNSIGNED NOT NULL,
    candidate_id BIGINT UNSIGNED NOT NULL,
    cover_letter TEXT NULL DEFAULT NULL,
    applied_at TIMESTAMP NOT NULL,
    created_at TIMESTAMP NULL DEFAULT NULL,
    updated_at TIMESTAMP NULL DEFAULT NULL,
    FOREIGN KEY (job_id) REFERENCES jobs(id) ON DELETE CASCADE,
    FOREIGN KEY (candidate_id) REFERENCES users(id) ON DELETE CASCADE
);

-- إضافة بيانات أولية لجدول users
INSERT INTO users (name, email, password, role, created_at, updated_at) VALUES
('Admin User', 'admin@example.com', '$2y$12$examplehashedpassword123', 'admin', NOW(), NOW()),
('Employer User', 'employer@example.com', '$2y$12$examplehashedpassword123', 'employer', NOW(), NOW()),
('Candidate User', 'candidate@example.com', '$2y$12$examplehashedpassword123', 'candidate', NOW(), NOW());

-- ملاحظة: كلمة المرور المشفرة هنا هي لـ "password123". في الواقع، لازم تستخدم كلمة مرور مشفرة حقيقية من Laravel باستخدام Hash::make('password123').

-- إضافة بيانات أولية لجدول jobs
INSERT INTO jobs (title, description, salary, location, company, experience, remote, employer_id, posted_date, status, created_at, updated_at) VALUES
('Frontend Developer', 'We are looking for a skilled Frontend Developer...', 60000.00, 'Remote', 'Tech Corp', 'mid', 1, 2, NOW(), 'approved', NOW(), NOW()),
('Backend Developer', 'We need a Backend Developer with Laravel experience...', 70000.00, 'Cairo, Egypt', 'Tech Corp', 'senior', 0, 2, NOW(), 'pending', NOW(), NOW());

-- إنشاء جدول migrations (عشان Laravel يعرف إن الـ Migrations اتشغلت)
CREATE TABLE migrations (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    migration VARCHAR(255) NOT NULL,
    batch INT NOT NULL
);

-- إضافة بيانات لجدول migrations
INSERT INTO migrations (migration, batch) VALUES
('2014_10_12_000000_create_users_table', 1),
('2025_xx_xx_xxxxxx_create_jobs_table', 1),
('2025_xx_xx_xxxxxx_create_applications_table', 1),
('2025_xx_xx_xxxxxx_create_personal_access_tokens_table', 1);
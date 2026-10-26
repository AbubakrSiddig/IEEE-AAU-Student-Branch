-- IEEE AAU Student Branch Platform Database Schema
-- Database: ieee_aau_db

CREATE TABLE IF NOT EXISTS `users` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `name` VARCHAR(191) NOT NULL,
  `email` VARCHAR(191) NOT NULL UNIQUE,
  `password` VARCHAR(255) NOT NULL,
  `role` ENUM('office_member', 'hr', 'programs_coordinator', 'magazine_coordinator', 'media_coordinator', 'registered_user') DEFAULT 'registered_user',
  `phone` VARCHAR(50) NULL,
  `is_active` TINYINT(1) DEFAULT 1,
  `email_verified_at` DATETIME NULL,
  `remember_token` VARCHAR(100) NULL,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `events` (
  `id` VARCHAR(50) PRIMARY KEY,
  `title` VARCHAR(191) NOT NULL,
  `description` TEXT NULL,
  `starts_at` DATETIME NOT NULL,
  `ends_at` DATETIME NOT NULL,
  `registration_deadline` DATETIME NULL,
  `location` VARCHAR(191) NOT NULL,
  `event_type` ENUM('in_person', 'online', 'hybrid') DEFAULT 'in_person',
  `meeting_link` VARCHAR(255) NULL,
  `speakers` VARCHAR(191) NULL,
  `seats` INT DEFAULT 100,
  `status` ENUM('upcoming', 'live', 'completed', 'cancelled') DEFAULT 'upcoming',
  `is_featured` TINYINT(1) DEFAULT 0,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `event_registrations` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `event_id` VARCHAR(50) NOT NULL,
  `user_id` INT NULL,
  `name` VARCHAR(191) NOT NULL,
  `email` VARCHAR(191) NOT NULL,
  `phone` VARCHAR(50) NULL,
  `status` ENUM('registered', 'waitlist', 'attended', 'did_not_attend', 'cancelled') DEFAULT 'registered',
  `registered_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (`event_id`) REFERENCES `events`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `teams` (
  `id` VARCHAR(50) PRIMARY KEY,
  `name_en` VARCHAR(100) NOT NULL,
  `name_ar` VARCHAR(100) NOT NULL,
  `description_en` TEXT NULL,
  `description_ar` TEXT NULL,
  `tasks_en` TEXT NULL,
  `tasks_ar` TEXT NULL,
  `is_active` TINYINT(1) DEFAULT 1,
  `order_index` INT DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `team_specialties` (
  `id` VARCHAR(50) PRIMARY KEY,
  `team_id` VARCHAR(50) NOT NULL,
  `name_en` VARCHAR(100) NOT NULL,
  `name_ar` VARCHAR(100) NOT NULL,
  FOREIGN KEY (`team_id`) REFERENCES `teams`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `recruitment_campaigns` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `title` VARCHAR(191) NOT NULL,
  `opens_at` DATETIME NOT NULL,
  `closes_at` DATETIME NOT NULL,
  `status` ENUM('draft', 'active', 'closed') DEFAULT 'active',
  `terms_version` VARCHAR(50) DEFAULT 'v2.0',
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `volunteer_applications` (
  `id` VARCHAR(50) PRIMARY KEY,
  `user_id` INT NULL,
  `campaign_id` INT NULL,
  `full_name` VARCHAR(191) NOT NULL,
  `university` VARCHAR(191) NOT NULL,
  `college` VARCHAR(191) NOT NULL,
  `major` VARCHAR(191) NOT NULL,
  `batch` VARCHAR(50) NOT NULL,
  `email` VARCHAR(191) NOT NULL,
  `phone` VARCHAR(50) NOT NULL,
  `whatsapp` VARCHAR(50) NOT NULL,
  `team_id` VARCHAR(50) NOT NULL,
  `specialty_id` VARCHAR(50) NULL,
  `general_answers` TEXT NULL,
  `team_answers` TEXT NULL,
  `why_choose_you` TEXT NULL,
  `status` ENUM('new', 'screening', 'candidate_interview', 'interview_scheduled', 'accepted', 'rejected') DEFAULT 'new',
  `reviewer_notes` TEXT NULL,
  `reviewer_score` INT NULL,
  `terms_version` VARCHAR(50) DEFAULT 'v2.0',
  `terms_accepted_at` DATETIME NULL,
  `submitted_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `members` (
  `id` VARCHAR(50) PRIMARY KEY,
  `user_id` INT NULL,
  `name` VARCHAR(191) NOT NULL,
  `email` VARCHAR(191) NOT NULL,
  `phone` VARCHAR(50) NULL,
  `team` VARCHAR(100) NOT NULL,
  `year` VARCHAR(50) NOT NULL,
  `role` VARCHAR(100) DEFAULT 'Member',
  `status` ENUM('Active', 'Inactive', 'Archived') DEFAULT 'Active',
  `join_date` DATE NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `news` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `title` VARCHAR(191) NOT NULL,
  `category` VARCHAR(100) NOT NULL,
  `snippet` TEXT NOT NULL,
  `content` TEXT NULL,
  `image` VARCHAR(500) NULL,
  `author` VARCHAR(100) NOT NULL,
  `status` ENUM('published', 'draft', 'archived') DEFAULT 'published',
  `published_at` DATETIME NULL,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `articles` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `title` VARCHAR(191) NOT NULL,
  `category` VARCHAR(100) NOT NULL,
  `snippet` TEXT NOT NULL,
  `content` TEXT NULL,
  `image` VARCHAR(500) NULL,
  `author` VARCHAR(100) NOT NULL,
  `status` ENUM('published', 'draft', 'archived') DEFAULT 'published',
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `magazine_issues` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `volume` VARCHAR(100) NOT NULL,
  `title` VARCHAR(191) NOT NULL,
  `cover` VARCHAR(500) NOT NULL,
  `description` TEXT NULL,
  `file_url` VARCHAR(500) NULL,
  `status` ENUM('published', 'hidden', 'archived') DEFAULT 'published',
  `year` VARCHAR(10) NOT NULL,
  `published_date` VARCHAR(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `audit_logs` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `user_id` INT NULL,
  `user_name` VARCHAR(191) NOT NULL,
  `action` VARCHAR(100) NOT NULL,
  `details` TEXT NOT NULL,
  `ip_address` VARCHAR(50) NULL,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Spec v0.3.4: Office Terms & Team Members
CREATE TABLE IF NOT EXISTS `office_terms` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `title` VARCHAR(191) NOT NULL,
  `starts_on` DATE NOT NULL,
  `ends_on` DATE NOT NULL,
  `status` ENUM('draft', 'current', 'archived') DEFAULT 'draft',
  `published_at` DATETIME NULL,
  `created_by` INT NULL,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `term_members` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `term_id` INT NOT NULL,
  `user_id` INT NULL,
  `name_en` VARCHAR(191) NOT NULL,
  `name_ar` VARCHAR(191) NOT NULL,
  `photo_path` VARCHAR(500) NULL,
  `bio_en` TEXT NULL,
  `bio_ar` TEXT NULL,
  `linkedin_url` VARCHAR(255) NULL,
  `email_public` VARCHAR(191) NULL,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  INDEX (`term_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `term_positions` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `term_id` INT NOT NULL,
  `member_id` INT NOT NULL,
  `position_group` ENUM('office', 'coordinator') NOT NULL,
  `position_key` VARCHAR(50) NOT NULL,
  `team_id` VARCHAR(50) NULL,
  `title_en` VARCHAR(191) NOT NULL,
  `title_ar` VARCHAR(191) NOT NULL,
  `sort_order` INT DEFAULT 0,
  `is_vacant` TINYINT(1) DEFAULT 0,
  INDEX (`term_id`),
  INDEX (`member_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


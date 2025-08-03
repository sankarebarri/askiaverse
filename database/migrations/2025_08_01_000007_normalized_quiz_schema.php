<?php
class NormalizedQuizSchema
{
    public function up(PDO $pdo): void
    {
        echo "🔄 Implementing fully normalized quiz schema...\n";
        
        // Step 1: Enhance existing options table with additional columns
        $pdo->exec("
            ALTER TABLE `options` 
            ADD COLUMN `explanation` TEXT NULL AFTER `is_correct`,
            ADD COLUMN `image_url` VARCHAR(512) NULL AFTER `explanation`,
            ADD COLUMN `display_order` TINYINT UNSIGNED NOT NULL DEFAULT 0 AFTER `image_url`
        ");
        echo "  - Enhanced options table with additional columns.\n";
        
        // Add indexes to options table
        $pdo->exec("
            ALTER TABLE `options` 
            ADD INDEX `idx_opt_question` (`question_id`),
            ADD INDEX `idx_opt_correct` (`question_id`, `is_correct`)
        ");
        echo "  - Added performance indexes to options table.\n";
        
        // Step 2: Update display_order for existing options
        $pdo->exec("
            UPDATE options o
            JOIN (
                SELECT id, question_id, 
                       ROW_NUMBER() OVER (PARTITION BY question_id ORDER BY id) - 1 as display_order
                FROM options 
                WHERE deleted_at IS NULL
            ) ranked ON o.id = ranked.id
            SET o.display_order = ranked.display_order
        ");
        echo "  - Updated display_order for existing options.\n";
        
        // Step 3: Remove JSON columns from questions table
        $pdo->exec("
            ALTER TABLE `questions` 
            DROP COLUMN `options`,
            DROP COLUMN `correct_answer_index`
        ");
        echo "  - Removed JSON columns from questions table.\n";
        
        // Step 4: Add additional useful columns to questions table
        $pdo->exec("
            ALTER TABLE `questions` 
            ADD COLUMN `difficulty_level` ENUM('easy', 'medium', 'hard') NOT NULL DEFAULT 'medium' AFTER `question_type`,
            ADD COLUMN `explanation` TEXT NULL AFTER `difficulty_level`,
            ADD COLUMN `image_url` VARCHAR(512) NULL AFTER `explanation`,
            ADD COLUMN `time_limit` INT UNSIGNED NULL DEFAULT 30 AFTER `image_url`,
            ADD INDEX `idx_question_difficulty` (`difficulty_level`),
            ADD INDEX `idx_question_quiz_order` (`quiz_id`, `display_order`)
        ");
        echo "  - Added enhanced columns to questions table.\n";
        
        // Step 5: Create admin_users table for content management
        $pdo->exec("
            CREATE TABLE `admin_users` (
              `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
              `username` VARCHAR(100) NOT NULL UNIQUE,
              `email` VARCHAR(255) NOT NULL UNIQUE,
              `password_hash` VARCHAR(255) NOT NULL,
              `role` ENUM('admin', 'content_creator', 'moderator') NOT NULL DEFAULT 'content_creator',
              `is_active` BOOLEAN NOT NULL DEFAULT TRUE,
              `last_login_at` TIMESTAMP NULL,
              `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
              `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
              `deleted_at` TIMESTAMP NULL,
              INDEX `idx_admin_username` (`username`),
              INDEX `idx_admin_email` (`email`),
              INDEX `idx_admin_role` (`role`),
              INDEX `idx_admin_active` (`is_active`)
            ) ENGINE=InnoDB CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
        ");
        echo "  - Created admin_users table for content management.\n";
        
        // Step 6: Create content_audit_log table for tracking changes
        $pdo->exec("
            CREATE TABLE `content_audit_log` (
              `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
              `admin_user_id` INT UNSIGNED NOT NULL,
              `table_name` VARCHAR(100) NOT NULL,
              `record_id` INT UNSIGNED NOT NULL,
              `action` ENUM('create', 'update', 'delete', 'restore') NOT NULL,
              `old_values` JSON NULL,
              `new_values` JSON NULL,
              `ip_address` VARCHAR(45) NULL,
              `user_agent` TEXT NULL,
              `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
              INDEX `idx_audit_admin` (`admin_user_id`),
              INDEX `idx_audit_table` (`table_name`),
              INDEX `idx_audit_record` (`table_name`, `record_id`),
              INDEX `idx_audit_action` (`action`),
              INDEX `idx_audit_created` (`created_at`),
              CONSTRAINT `fk_audit_admin` FOREIGN KEY (`admin_user_id`) REFERENCES `admin_users`(`id`) ON DELETE RESTRICT
            ) ENGINE=InnoDB CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
        ");
        echo "  - Created content_audit_log table for tracking changes.\n";
        
        echo "✅ Fully normalized quiz schema implemented!\n";
    }

    public function down(PDO $pdo): void
    {
        echo "🔄 Rolling back normalized quiz schema...\n";
        
        // Drop audit log table
        $pdo->exec("DROP TABLE IF EXISTS `content_audit_log`;");
        echo "  - Dropped content_audit_log table.\n";
        
        // Drop admin users table
        $pdo->exec("DROP TABLE IF EXISTS `admin_users`;");
        echo "  - Dropped admin_users table.\n";
        
        // Remove enhanced columns from questions
        $pdo->exec("
            ALTER TABLE `questions` 
            DROP COLUMN `difficulty_level`,
            DROP COLUMN `explanation`,
            DROP COLUMN `image_url`,
            DROP COLUMN `time_limit`
        ");
        echo "  - Removed enhanced columns from questions table.\n";
        
        // Add back JSON columns to questions
        $pdo->exec("
            ALTER TABLE `questions` 
            ADD COLUMN `options` JSON NULL AFTER `text`,
            ADD COLUMN `correct_answer_index` TINYINT UNSIGNED NULL AFTER `options`
        ");
        echo "  - Added back JSON columns to questions table.\n";
        
        // Drop options table
        $pdo->exec("DROP TABLE IF EXISTS `options`;");
        echo "  - Dropped options table.\n";
        
        echo "✅ Rollback completed!\n";
    }
} 
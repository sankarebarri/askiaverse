<?php
class CreateQuizEngineTables
{
    public function up(PDO $pdo): void
    {
        // Create tables in order of dependency: subjects -> topics -> quizzes -> questions -> options
        
        $pdo->exec("
            CREATE TABLE `subjects` (
              `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
              `name` VARCHAR(100) NOT NULL,
              `description` TEXT NULL,
              `deleted_at` TIMESTAMP NULL,
              INDEX `idx_subj_deleted` (`deleted_at`)
            ) ENGINE=InnoDB CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
        ");
        echo "  - Migrated: subjects table created.\n";

        $pdo->exec("
            CREATE TABLE `topics` (
              `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
              `subject_id` INT UNSIGNED NOT NULL,
              `name` VARCHAR(100) NOT NULL,
              `display_order` INT NOT NULL DEFAULT 0,
              `deleted_at` TIMESTAMP NULL,
              CONSTRAINT `fk_topic_subj` FOREIGN KEY (`subject_id`) REFERENCES `subjects`(`id`) ON DELETE CASCADE,
              INDEX `idx_topic_deleted` (`deleted_at`)
            ) ENGINE=InnoDB CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
        ");
        echo "  - Migrated: topics table created.\n";

        $pdo->exec("
            CREATE TABLE `quizzes` (
              `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
              `topic_id` INT UNSIGNED NOT NULL,
              `title` VARCHAR(255) NOT NULL,
              `description` TEXT NULL,
              `deleted_at` TIMESTAMP NULL,
              CONSTRAINT `fk_quiz_topic` FOREIGN KEY (`topic_id`) REFERENCES `topics`(`id`) ON DELETE CASCADE,
              INDEX `idx_quiz_deleted` (`deleted_at`)
            ) ENGINE=InnoDB CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
        ");
        echo "  - Migrated: quizzes table created.\n";

        $pdo->exec("
            CREATE TABLE `questions` (
              `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
              `quiz_id` INT UNSIGNED NOT NULL,
              `text` TEXT NOT NULL,
              `media_url` VARCHAR(512) NULL,
              `display_order` INT NOT NULL DEFAULT 0,
              `question_type` ENUM('regular','jackpot') NOT NULL DEFAULT 'regular',
              `jackpot_value` INT UNSIGNED NULL,
              `deleted_at` TIMESTAMP NULL,
              CONSTRAINT `fk_q_question` FOREIGN KEY (`quiz_id`) REFERENCES `quizzes`(`id`) ON DELETE CASCADE,
              CONSTRAINT `chk_jackpot` CHECK (
                (`question_type` = 'regular' AND `jackpot_value` IS NULL)
                OR
                (`question_type` = 'jackpot' AND `jackpot_value` IS NOT NULL)
              ),
              INDEX `idx_question_deleted` (`deleted_at`)
            ) ENGINE=InnoDB CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
        ");
        echo "  - Migrated: questions table created.\n";

        $pdo->exec("
            CREATE TABLE `options` (
              `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
              `question_id` INT UNSIGNED NOT NULL,
              `text` TEXT NOT NULL,
              `is_correct` BOOLEAN NOT NULL DEFAULT FALSE,
              `deleted_at` TIMESTAMP NULL,
              CONSTRAINT `fk_opt_question` FOREIGN KEY (`question_id`) REFERENCES `questions`(`id`) ON DELETE CASCADE,
              INDEX `idx_opt_deleted` (`deleted_at`)
            ) ENGINE=InnoDB CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
        ");
        echo "  - Migrated: options table created.\n";
    }

    public function down(PDO $pdo): void
    {
        // Drop tables in reverse order of dependency
        $pdo->exec("DROP TABLE IF EXISTS `options`;");
        $pdo->exec("DROP TABLE IF EXISTS `questions`;");
        $pdo->exec("DROP TABLE IF EXISTS `quizzes`;");
        $pdo->exec("DROP TABLE IF EXISTS `topics`;");
        $pdo->exec("DROP TABLE IF EXISTS `subjects`;");
        echo "  - Rolled back: Core quiz engine tables dropped.\n";
    }
}

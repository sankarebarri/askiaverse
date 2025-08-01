<?php
class CreateQuizAttemptTables
{
    public function up(PDO $pdo): void
    {
        $pdo->exec("
            CREATE TABLE `quiz_attempts` (
              `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
              `user_id` INT UNSIGNED NOT NULL,
              `quiz_id` INT UNSIGNED NOT NULL,
              `started_at` TIMESTAMP NULL,
              `finished_at` TIMESTAMP NULL,
              `score` INT NOT NULL DEFAULT 0,
              `xp_earned` INT NOT NULL DEFAULT 0,
              `orbs_earned` INT NOT NULL DEFAULT 0,
              CONSTRAINT `fk_qa_user` FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE CASCADE,
              CONSTRAINT `fk_qa_quiz` FOREIGN KEY (`quiz_id`) REFERENCES `quizzes`(`id`) ON DELETE CASCADE,
              INDEX `idx_qa_user_quiz` (`user_id`, `quiz_id`)
            ) ENGINE=InnoDB CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
        ");
        echo "  - Migrated: quiz_attempts table created.\n";

        $pdo->exec("
            CREATE TABLE `attempt_answers` (
              `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
              `attempt_id` INT UNSIGNED NOT NULL,
              `question_id` INT UNSIGNED NOT NULL,
              `option_id` INT UNSIGNED NOT NULL,
              `is_correct` BOOLEAN NOT NULL,
              `answered_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
              `time_taken_ms` INT UNSIGNED NULL,
              CONSTRAINT `fk_aa_attempt` FOREIGN KEY (`attempt_id`) REFERENCES `quiz_attempts`(`id`) ON DELETE CASCADE,
              CONSTRAINT `fk_aa_question` FOREIGN KEY (`question_id`) REFERENCES `questions`(`id`) ON DELETE CASCADE,
              CONSTRAINT `fk_aa_option` FOREIGN KEY (`option_id`) REFERENCES `options`(`id`) ON DELETE CASCADE,
              INDEX `idx_aa_attempt_question` (`attempt_id`, `question_id`)
            ) ENGINE=InnoDB CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
        ");
        echo "  - Migrated: attempt_answers table created.\n";
    }

    public function down(PDO $pdo): void
    {
        $pdo->exec("DROP TABLE IF EXISTS `attempt_answers`;");
        $pdo->exec("DROP TABLE IF EXISTS `quiz_attempts`;");
        echo "  - Rolled back: Quiz attempt tables dropped.\n";
    }
}


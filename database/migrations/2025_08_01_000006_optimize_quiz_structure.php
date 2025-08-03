<?php
class OptimizeQuizStructure
{
    public function up(PDO $pdo): void
    {
        echo "🔄 Optimizing quiz database structure...\n";
        
        // Step 1: Add new columns to questions table
        $pdo->exec("
            ALTER TABLE `questions` 
            ADD COLUMN `options` JSON NULL AFTER `text`,
            ADD COLUMN `correct_answer_index` TINYINT UNSIGNED NULL AFTER `options`
        ");
        echo "  - Added options and correct_answer_index columns to questions table.\n";
        
        // Step 2: Migrate existing data from options table to questions table
        $pdo->exec("
            UPDATE questions q 
            SET 
                q.options = (
                    SELECT JSON_ARRAYAGG(
                        JSON_OBJECT(
                            'text', o.text,
                            'is_correct', o.is_correct
                        )
                    )
                    FROM options o 
                    WHERE o.question_id = q.id AND o.deleted_at IS NULL
                ),
                q.correct_answer_index = (
                    SELECT o.id - MIN(o2.id)
                    FROM options o
                    JOIN options o2 ON o2.question_id = o.question_id AND o2.deleted_at IS NULL
                    WHERE o.question_id = q.id AND o.is_correct = 1 AND o.deleted_at IS NULL
                    LIMIT 1
                )
            WHERE EXISTS (SELECT 1 FROM options WHERE question_id = q.id AND deleted_at IS NULL)
        ");
        echo "  - Migrated existing options data to JSON format.\n";
        
        // Step 3: Drop the options table (no longer needed)
        $pdo->exec("DROP TABLE IF EXISTS `options`;");
        echo "  - Dropped options table (no longer needed).\n";
        
        echo "✅ Quiz structure optimization completed!\n";
    }

    public function down(PDO $pdo): void
    {
        echo "🔄 Rolling back quiz structure optimization...\n";
        
        // Recreate options table
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
        echo "  - Recreated options table.\n";
        
        // Remove new columns from questions table
        $pdo->exec("
            ALTER TABLE `questions` 
            DROP COLUMN `options`,
            DROP COLUMN `correct_answer_index`
        ");
        echo "  - Removed options and correct_answer_index columns from questions table.\n";
        
        echo "✅ Rollback completed!\n";
    }
} 
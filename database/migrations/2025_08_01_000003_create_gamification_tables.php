<?php
class CreateGamificationTables
{
    public function up(PDO $pdo): void
    {
        // --- XP Log Table ---
        $pdo->exec("
            CREATE TABLE `xp_log` (
              `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
              `user_id` INT UNSIGNED NOT NULL,
              `amount` INT NOT NULL,
              `source_type` VARCHAR(50) NOT NULL,
              `source_id` INT UNSIGNED NULL,
              `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
              CONSTRAINT `fk_xp_user` FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE CASCADE,
              INDEX `idx_xp_user_created` (`user_id`, `created_at`)
            ) ENGINE=InnoDB CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
        ");
        echo "  - Migrated: xp_log table created.\n";

        // --- Orb Log Table ---
        $pdo->exec("
            CREATE TABLE `orb_log` (
              `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
              `user_id` INT UNSIGNED NOT NULL,
              `amount` INT NOT NULL,
              `source_type` VARCHAR(50) NOT NULL,
              `source_id` INT UNSIGNED NULL,
              `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
              CONSTRAINT `fk_orb_user` FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE CASCADE,
              INDEX `idx_orb_user_created` (`user_id`, `created_at`)
            ) ENGINE=InnoDB CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
        ");
        echo "  - Migrated: orb_log table created.\n";
    }

    public function down(PDO $pdo): void
    {
        $pdo->exec("DROP TABLE IF EXISTS `orb_log`;");
        $pdo->exec("DROP TABLE IF EXISTS `xp_log`;");
        echo "  - Rolled back: Gamification tables dropped.\n";
    }
}


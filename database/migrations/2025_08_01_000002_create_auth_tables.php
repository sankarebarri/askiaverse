<?php
class CreateAuthTables
{
    /**
     * Run the migrations for authentication-related tables.
     */
    public function up(PDO $pdo): void
    {
        // --- Password Resets Table ---
        $pdo->exec("
            CREATE TABLE `password_resets` (
              `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
              `user_id` INT UNSIGNED NOT NULL,
              `token_hash` CHAR(64) NOT NULL,
              `used` BOOLEAN NOT NULL DEFAULT FALSE,
              `expires_at` TIMESTAMP NOT NULL,
              `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
              CONSTRAINT `fk_pr_user` FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE CASCADE,
              INDEX `idx_pr_token` (`token_hash`),
              INDEX `idx_pr_user` (`user_id`)
            ) ENGINE=InnoDB CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
        ");
        echo "  - Migrated: password_resets table created.\n";

        // --- Email Verifications Table ---
        $pdo->exec("
            CREATE TABLE `email_verifications` (
              `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
              `user_id` INT UNSIGNED NOT NULL,
              `token_hash` CHAR(64) NOT NULL,
              `expires_at` TIMESTAMP NOT NULL,
              `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
              CONSTRAINT `fk_ev_user` FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE CASCADE,
              INDEX `idx_ev_token` (`token_hash`),
              INDEX `idx_ev_user` (`user_id`)
            ) ENGINE=InnoDB CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
        ");
        echo "  - Migrated: email_verifications table created.\n";
        
        // --- Sessions Table ---
        $pdo->exec("
            CREATE TABLE `sessions` (
              `id` CHAR(36) PRIMARY KEY COMMENT 'UUID v4',
              `user_id` INT UNSIGNED NOT NULL,
              `token_hash` CHAR(64) NOT NULL,
              `user_agent` VARCHAR(255) NULL,
              `ip_address` VARCHAR(45) NULL,
              `expires_at` TIMESTAMP NOT NULL,
              `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
              CONSTRAINT `fk_sess_user` FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE CASCADE,
              INDEX `idx_sess_user` (`user_id`),
              INDEX `idx_sess_expires` (`expires_at`)
            ) ENGINE=InnoDB CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
        ");
        echo "  - Migrated: sessions table created.\n";
    }

    /**
     * Reverse the migrations.
     */
    public function down(PDO $pdo): void
    {
        $pdo->exec("DROP TABLE IF EXISTS `sessions`;");
        $pdo->exec("DROP TABLE IF EXISTS `email_verifications`;");
        $pdo->exec("DROP TABLE IF EXISTS `password_resets`;");
        echo "  - Rolled back: Authentication tables dropped.\n";
    }
}

<?php

// We will create a simple, custom migration system.
// This is the first migration file.
// Filename: database/migrations/2025_08_01_000001_create_users_table.php

class CreateUsersTable
{
    /**
     * Run the migration to create the table.
     *
     * @param PDO $pdo The database connection.
     * @return void
     */
    public function up(PDO $pdo): void
    {
        // This SQL is taken directly from our approved schema in the Canvas.
        $sql = "
            CREATE TABLE `users` (
              `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
              `username` VARCHAR(50) NOT NULL UNIQUE,
              `email` VARCHAR(255) NULL UNIQUE,
              `password_hash` VARCHAR(255) NOT NULL,
              `display_name` VARCHAR(100) NULL,
              `avatar_url` VARCHAR(512) NULL,
              
              -- Profile fields
              `school` VARCHAR(255) NULL,
              `city` VARCHAR(100) NULL,
              `grade` VARCHAR(50) NULL,
              `country_code` CHAR(2) NULL,

              -- Gamification
              `xp` INT UNSIGNED NOT NULL DEFAULT 0,
              `orbs` INT UNSIGNED NOT NULL DEFAULT 0,
              `level` INT UNSIGNED NOT NULL DEFAULT 1,
              `status` ENUM('active','pending','suspended','banned') NOT NULL DEFAULT 'active',

              -- Soft deletes & timestamps
              `deleted_at` TIMESTAMP NULL,
              `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
              `updated_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

              INDEX `idx_users_deleted_at` (`deleted_at`)
            ) ENGINE=InnoDB CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
        ";

        // Execute the SQL statement.
        $pdo->exec($sql);

        echo "Migrated: users table created successfully.\n";
    }

    /**
     * Reverse the migration to drop the table.
     *
     * @param PDO $pdo The database connection.
     * @return void
     */
    public function down(PDO $pdo): void
    {
        // This SQL will drop the table if we need to reverse the migration.
        $sql = "DROP TABLE IF EXISTS `users`;";

        // Execute the SQL statement.
        $pdo->exec($sql);
        
        echo "Rolled back: users table dropped successfully.\n";
    }
}

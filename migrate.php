<?php

// Filename: migrate.php (place this in the root of your project)

// --- BOOTSTRAP ---
// A very simple function to parse the .env file
function loadEnv(string $path): void
{
    if (!file_exists($path)) {
        throw new Exception(".env file not found at {$path}");
    }
    $lines = file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        if (strpos(trim($line), '#') === 0) {
            continue;
        }
        list($name, $value) = explode('=', $line, 2);
        $name = trim($name);
        $value = trim($value);
        if (!array_key_exists($name, $_SERVER) && !array_key_exists($name, $_ENV)) {
            putenv(sprintf('%s=%s', $name, $value));
            $_ENV[$name] = $value;
            $_SERVER[$name] = $value;
        }
    }
}

// Load environment variables from .env file
loadEnv(__DIR__ . '/.env');

// --- DATABASE CONNECTION ---
function getPDOConnection(): PDO
{
    $host = getenv('DB_HOST');
    $port = getenv('DB_PORT');
    $db   = getenv('DB_DATABASE');
    $user = getenv('DB_USERNAME');
    $pass = getenv('DB_PASSWORD');
    $charset = 'utf8mb4';

    $dsn = "mysql:host=$host;port=$port;dbname=$db;charset=$charset";
    $options = [
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES   => false,
    ];

    try {
        return new PDO($dsn, $user, $pass, $options);
    } catch (PDOException $e) {
        throw new PDOException($e->getMessage(), (int)$e->getCode());
    }
}

// --- MIGRATION LOGIC ---
try {
    $pdo = getPDOConnection();
    echo "Database connection successful.\n";

    // 1. Ensure the migrations table exists to track which migrations have run
    $pdo->exec("
        CREATE TABLE IF NOT EXISTS `migrations` (
          `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
          `migration` VARCHAR(255) NOT NULL,
          `batch` INT NOT NULL
        ) ENGINE=InnoDB CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
    ");

    // 2. Get all migrations that have already been run
    $ranMigrationsStmt = $pdo->query("SELECT migration FROM migrations");
    $ranMigrations = $ranMigrationsStmt->fetchAll(PDO::FETCH_COLUMN);

    // 3. Get all available migration files from the directory
    $migrationFiles = glob(__DIR__ . '/database/migrations/*.php');
    
    // 4. Determine which migrations need to be run
    $pendingMigrations = array_filter($migrationFiles, function ($file) use ($ranMigrations) {
        return !in_array(basename($file), $ranMigrations);
    });

    if (empty($pendingMigrations)) {
        echo "No new migrations to run.\n";
        exit;
    }

    echo "Found " . count($pendingMigrations) . " new migrations to run.\n";

    // Get the latest batch number
    $latestBatchStmt = $pdo->query("SELECT MAX(batch) as max_batch FROM migrations");
    $latestBatch = $latestBatchStmt->fetchColumn();
    $batch = $latestBatch ? $latestBatch + 1 : 1;

    // 5. Run the pending migrations
    foreach ($pendingMigrations as $file) {
        echo "Migrating: " . basename($file) . "\n";
        
        // Include the migration file
        require_once $file;
        
        // Get the class name from the filename
        // e.g., 2025_08_01_000001_create_users_table.php -> CreateUsersTable
        $className = implode('', array_map('ucfirst', explode('_', substr(basename($file, '.php'), 18))));
        
        if (class_exists($className)) {
            $migration = new $className();
            $migration->up($pdo);

            // Record the migration in the database
            $stmt = $pdo->prepare("INSERT INTO migrations (migration, batch) VALUES (?, ?)");
            $stmt->execute([basename($file), $batch]);
        } else {
            echo "Error: Class {$className} not found in " . basename($file) . "\n";
        }
    }

    echo "All migrations completed successfully.\n";

} catch (Exception $e) {
    echo "An error occurred: " . $e->getMessage() . "\n";
}

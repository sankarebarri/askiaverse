<?php
// Database update script to add orbs column
// Run this once to update your database

$host = '127.0.0.1';
$port = 3306;
$dbname = 'askiaverse';
$username_db = 'root';
$password_db = '';

try {
    $dsn = "mysql:host={$host};port={$port};dbname={$dbname};charset=utf8mb4";
    $options = [
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES   => false,
    ];
    $pdo = new PDO($dsn, $username_db, $password_db, $options);
    
    echo "<h2>Updating Askiaverse Database...</h2>";
    
    // Check if orbs column already exists
    $sql = "SHOW COLUMNS FROM user_progress LIKE 'orbs'";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $columnExists = $stmt->fetch();
    
    if ($columnExists) {
        echo "<p style='color: green;'>✅ Orbs column already exists in user_progress table.</p>";
    } else {
        // Add orbs column
        $sql = "ALTER TABLE user_progress ADD COLUMN orbs int(11) DEFAULT 0 AFTER experience";
        $pdo->exec($sql);
        echo "<p style='color: green;'>✅ Added orbs column to user_progress table.</p>";
        
        // Update existing records
        $sql = "UPDATE user_progress SET orbs = 0 WHERE orbs IS NULL";
        $pdo->exec($sql);
        echo "<p style='color: green;'>✅ Updated existing records with 0 orbs.</p>";
    }
    
    // Show current table structure
    echo "<h3>Current user_progress table structure:</h3>";
    $sql = "DESCRIBE user_progress";
    $stmt = $pdo->query($sql);
    $columns = $stmt->fetchAll();
    
    echo "<table border='1' style='border-collapse: collapse; margin: 20px 0;'>";
    echo "<tr><th>Field</th><th>Type</th><th>Null</th><th>Key</th><th>Default</th><th>Extra</th></tr>";
    foreach ($columns as $column) {
        echo "<tr>";
        echo "<td>{$column['Field']}</td>";
        echo "<td>{$column['Type']}</td>";
        echo "<td>{$column['Null']}</td>";
        echo "<td>{$column['Key']}</td>";
        echo "<td>{$column['Default']}</td>";
        echo "<td>{$column['Extra']}</td>";
        echo "</tr>";
    }
    echo "</table>";
    
    echo "<p style='color: green;'>✅ Database update completed successfully!</p>";
    echo "<p><a href='http://localhost:8000/'>Return to Askiaverse</a></p>";
    
} catch (Exception $e) {
    echo "<p style='color: red;'>❌ Error updating database: " . $e->getMessage() . "</p>";
}
?>

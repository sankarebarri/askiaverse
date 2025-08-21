<?php
// Simple subjects page for testing
require_once __DIR__ . '/../src/bootstrap.php';

try {
    $pdo = getDatabaseConnection();
    
    // Fetch all subjects with their themes
    $sql = "
        SELECT 
            s.id,
            s.name,
            s.description,
            s.icon,
            s.color,
            COUNT(t.id) as theme_count
        FROM subjects s
        LEFT JOIN themes t ON s.id = t.subject_id
        GROUP BY s.id
        ORDER BY s.name
    ";
    
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $subjects = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    // Include the view
    include __DIR__ . '/../resources/views/subjects/index.php';
    
} catch (Exception $e) {
    echo "Erreur: " . $e->getMessage();
}
?>

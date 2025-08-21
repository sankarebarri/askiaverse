<?php
// Simple individual subject page for testing
require_once __DIR__ . '/../src/bootstrap.php';

$subjectId = $_GET['id'] ?? 1;

try {
    $pdo = getDatabaseConnection();
    
    // Fetch subject details
    $sql = "SELECT * FROM subjects WHERE id = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$subjectId]);
    $subject = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if (!$subject) {
        echo "Matière non trouvée";
        exit;
    }
    
    // Fetch themes for this subject
    $sql = "SELECT * FROM themes WHERE subject_id = ? ORDER BY difficulty, name";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$subjectId]);
    $themes = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    // Fetch sample questions for this subject
    $sql = "
        SELECT 
            q.*,
            t.name as theme_name
        FROM questions q
        JOIN themes t ON q.theme_id = t.id
        WHERE t.subject_id = ?
        ORDER BY RAND()
        LIMIT 5
    ";
    
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$subjectId]);
    $questions = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    // Include the view
    include __DIR__ . '/../resources/views/subjects/show.php';
    
} catch (Exception $e) {
    echo "Erreur: " . $e->getMessage();
}
?>

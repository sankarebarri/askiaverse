<?php
// database/seeds/initial_data.php

// Charger le bootstrap (retourne un objet PDO)
$pdo = require __DIR__ . '/../../src/bootstrap.php';

echo "🌱 Début du seeding des données initiales...\n";

try {
    // 1. Insérer l'utilisateur hamza
    $userSql = "
        INSERT INTO users (
            username, 
            email, 
            password_hash, 
            display_name, 
            school, 
            city, 
            grade, 
            country_code,
            xp,
            orbs,
            level,
            status
        ) VALUES (
            'hamza',
            'hamza@askiaverse.ml',
            :password_hash,
            'Hamza',
            'LYMG',
            'Gao',
            '6ème',
            'ML',
            150,
            500,
            3,
            'active'
        )
    ";
    
    $passwordHash = password_hash('123456', PASSWORD_DEFAULT);
    $stmt = $pdo->prepare($userSql);
    $stmt->execute(['password_hash' => $passwordHash]);
    echo "✅ Utilisateur 'hamza' créé avec succès\n";

    // 2. Insérer le sujet Mathématiques
    $subjectSql = "
        INSERT INTO subjects (name, description) VALUES (
            'Mathématiques',
            'Apprentissage des concepts mathématiques fondamentaux'
        )
    ";
    $pdo->exec($subjectSql);
    $subjectId = $pdo->lastInsertId();
    echo "✅ Sujet 'Mathématiques' créé avec l'ID: $subjectId\n";

    // 3. Insérer le thème Arithmétique
    $topicSql = "
        INSERT INTO topics (subject_id, name, display_order) VALUES (
            :subject_id,
            'Arithmétique Simple',
            1
        )
    ";
    $stmt = $pdo->prepare($topicSql);
    $stmt->execute(['subject_id' => $subjectId]);
    $topicId = $pdo->lastInsertId();
    echo "✅ Thème 'Arithmétique Simple' créé avec l'ID: $topicId\n";

    // 4. Insérer 2 questions
    $questions = [
        [
            'subject_id' => $subjectId,
            'topic_id' => $topicId,
            'question_text' => 'Combien font 9 - 4 ?',
            'option_a' => '3',
            'option_b' => '4',
            'option_c' => '5',
            'option_d' => '6',
            'correct_answer' => 'C',
            'explanation' => '9 - 4 = 5. C\'est une soustraction simple.',
            'difficulty' => 'easy',
            'points' => 10
        ],
        [
            'subject_id' => $subjectId,
            'topic_id' => $topicId,
            'question_text' => 'Quel est le résultat de 7 + 8 ?',
            'option_a' => '13',
            'option_b' => '14',
            'option_c' => '15',
            'option_d' => '16',
            'correct_answer' => 'C',
            'explanation' => '7 + 8 = 15. C\'est une addition simple.',
            'difficulty' => 'easy',
            'points' => 10
        ]
    ];

    $questionSql = "
        INSERT INTO questions (
            subject_id,
            topic_id,
            question_text,
            option_a,
            option_b,
            option_c,
            option_d,
            correct_answer,
            explanation,
            difficulty,
            points
        ) VALUES (
            :subject_id,
            :topic_id,
            :question_text,
            :option_a,
            :option_b,
            :option_c,
            :option_d,
            :correct_answer,
            :explanation,
            :difficulty,
            :points
        )
    ";

    $stmt = $pdo->prepare($questionSql);
    foreach ($questions as $question) {
        $stmt->execute($question);
        echo "✅ Question créée: {$question['question_text']}\n";
    }

    echo "\n🎉 Seeding terminé avec succès!\n";
    echo "📊 Résumé:\n";
    echo "   - 1 utilisateur (hamza/123456)\n";
    echo "   - 1 sujet (Mathématiques)\n";
    echo "   - 1 thème (Arithmétique Simple)\n";
    echo "   - 2 questions\n";

} catch (PDOException $e) {
    echo "❌ Erreur lors du seeding: " . $e->getMessage() . "\n";
    exit(1);
} 
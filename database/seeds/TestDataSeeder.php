<?php
// database/seeds/TestDataSeeder.php

require_once __DIR__ . '/../../vendor/autoload.php';
require_once __DIR__ . '/../../config/config.php';
require_once __DIR__ . '/../../src/Shared/Database.php';
require_once __DIR__ . '/../../src/Shared/Config.php';

use Shared\Database;

class TestDataSeeder {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance();
    }

    public function run() {
        echo "🌱 Démarrage du seeding des données de test...\n";

        try {
            $this->db->beginTransaction();

            // 1. Créer l'utilisateur hamza
            $this->createUser();
            echo "✅ Utilisateur 'hamza' créé\n";

            // 2. Créer les matières
            $this->createSubjects();
            echo "✅ Matières créées\n";

            // 3. Créer les thèmes
            $this->createThemes();
            echo "✅ Thèmes créés\n";

            // 4. Créer les questions
            $this->createQuestions();
            echo "✅ Questions créées\n";

            $this->db->commit();
            echo "🎉 Seeding terminé avec succès!\n";

        } catch (Exception $e) {
            $this->db->rollback();
            echo "❌ Erreur lors du seeding: " . $e->getMessage() . "\n";
        }
    }

    private function createUser() {
        // Vérifier si l'utilisateur existe déjà
        $existingUser = $this->db->fetch("SELECT id FROM users WHERE username = :username", ['username' => 'hamza']);
        
        if ($existingUser) {
            echo "⚠️  L'utilisateur 'hamza' existe déjà\n";
            return;
        }

        $userData = [
            'username' => 'hamza',
            'password' => password_hash('123456', PASSWORD_DEFAULT),
            'email' => 'hamza@askiaverse.local',
            'school' => 'LYMG',
            'state' => 'Gao',
            'city' => 'Gao',
            'class_level' => '6ème',
            'created_at' => date('Y-m-d H:i:s'),
            'last_login' => null
        ];

        $this->db->insert('users', $userData);
    }

    private function createSubjects() {
        $subjects = [
            [
                'name' => 'Mathématiques',
                'description' => 'Apprentissage des concepts mathématiques fondamentaux',
                'icon' => '🔢',
                'color' => '#4A90E2',
                'created_at' => date('Y-m-d H:i:s')
            ],
            [
                'name' => 'Français',
                'description' => 'Maîtrise de la langue française',
                'icon' => '📚',
                'color' => '#50C878',
                'created_at' => date('Y-m-d H:i:s')
            ],
            [
                'name' => 'Histoire-Géographie',
                'description' => 'Découverte de l\'histoire et de la géographie',
                'icon' => '🌍',
                'color' => '#FF6B35',
                'created_at' => date('Y-m-d H:i:s')
            ]
        ];

        foreach ($subjects as $subject) {
            // Vérifier si la matière existe déjà
            $existing = $this->db->fetch("SELECT id FROM subjects WHERE name = :name", ['name' => $subject['name']]);
            if (!$existing) {
                $this->db->insert('subjects', $subject);
            }
        }
    }

    private function createThemes() {
        // Récupérer l'ID de la matière Mathématiques
        $mathSubject = $this->db->fetch("SELECT id FROM subjects WHERE name = :name", ['name' => 'Mathématiques']);
        
        if (!$mathSubject) {
            throw new Exception("Matière 'Mathématiques' non trouvée");
        }

        $themes = [
            [
                'name' => 'Arithmétique Simple',
                'description' => 'Opérations de base : addition, soustraction, multiplication, division',
                'subject_id' => $mathSubject['id'],
                'difficulty' => 'facile',
                'created_at' => date('Y-m-d H:i:s')
            ],
            [
                'name' => 'Géométrie de Base',
                'description' => 'Formes géométriques et calculs de périmètre et aire',
                'subject_id' => $mathSubject['id'],
                'difficulty' => 'moyen',
                'created_at' => date('Y-m-d H:i:s')
            ]
        ];

        foreach ($themes as $theme) {
            // Vérifier si le thème existe déjà
            $existing = $this->db->fetch("SELECT id FROM themes WHERE name = :name AND subject_id = :subject_id", 
                ['name' => $theme['name'], 'subject_id' => $theme['subject_id']]);
            if (!$existing) {
                $this->db->insert('themes', $theme);
            }
        }
    }

    private function createQuestions() {
        // Récupérer l'ID du thème Arithmétique Simple
        $arithmeticTheme = $this->db->fetch("SELECT id FROM themes WHERE name = :name", ['name' => 'Arithmétique Simple']);
        
        if (!$arithmeticTheme) {
            throw new Exception("Thème 'Arithmétique Simple' non trouvé");
        }

        $questions = [
            [
                'question' => 'Combien font 9 - 4 ?',
                'options' => json_encode(['3', '4', '5', '6']),
                'correct_answer' => 2, // Index 2 = "5"
                'explanation' => '9 - 4 = 5. La soustraction retire 4 de 9.',
                'theme_id' => $arithmeticTheme['id'],
                'difficulty' => 'facile',
                'points' => 10,
                'created_at' => date('Y-m-d H:i:s')
            ],
            [
                'question' => 'Quel est le résultat de 7 + 8 ?',
                'options' => json_encode(['13', '14', '15', '16']),
                'correct_answer' => 2, // Index 2 = "15"
                'explanation' => '7 + 8 = 15. L\'addition combine les deux nombres.',
                'theme_id' => $arithmeticTheme['id'],
                'difficulty' => 'facile',
                'points' => 10,
                'created_at' => date('Y-m-d H:i:s')
            ],
            [
                'question' => 'Combien font 6 × 7 ?',
                'options' => json_encode(['40', '42', '44', '46']),
                'correct_answer' => 1, // Index 1 = "42"
                'explanation' => '6 × 7 = 42. La multiplication répète l\'addition.',
                'theme_id' => $arithmeticTheme['id'],
                'difficulty' => 'moyen',
                'points' => 15,
                'created_at' => date('Y-m-d H:i:s')
            ],
            [
                'question' => 'Quel est le résultat de 20 ÷ 4 ?',
                'options' => json_encode(['4', '5', '6', '8']),
                'correct_answer' => 1, // Index 1 = "5"
                'explanation' => '20 ÷ 4 = 5. La division partage en groupes égaux.',
                'theme_id' => $arithmeticTheme['id'],
                'difficulty' => 'moyen',
                'points' => 15,
                'created_at' => date('Y-m-d H:i:s')
            ],
            [
                'question' => 'Combien font 15 + 23 ?',
                'options' => json_encode(['36', '37', '38', '39']),
                'correct_answer' => 2, // Index 2 = "38"
                'explanation' => '15 + 23 = 38. Additionnez les unités puis les dizaines.',
                'theme_id' => $arithmeticTheme['id'],
                'difficulty' => 'facile',
                'points' => 10,
                'created_at' => date('Y-m-d H:i:s')
            ]
        ];

        foreach ($questions as $question) {
            // Vérifier si la question existe déjà
            $existing = $this->db->fetch("SELECT id FROM questions WHERE question = :question AND theme_id = :theme_id", 
                ['question' => $question['question'], 'theme_id' => $question['theme_id']]);
            if (!$existing) {
                $this->db->insert('questions', $question);
            }
        }
    }
}

// Exécuter le seeder si le script est appelé directement
if (php_sapi_name() === 'cli') {
    $seeder = new TestDataSeeder();
    $seeder->run();
} 
<?php
// FICHIER : database/seeds/DatabaseSeeder.php
// Ce fichier contient la logique pour insérer nos données de test.
// ===================================================================

class DatabaseSeeder
{
    private PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function run(): void
    {
        echo "Début du seeding de la base de données...\n";

        // On exécute les méthodes de seeding dans un ordre logique
        $this->seedSubjectsAndTopics();
        $this->seedQuizzesAndQuestions();

        echo "Seeding terminé avec succès !\n";
    }

    private function seedSubjectsAndTopics(): void
    {
        echo "  - Seeding des matières et des thèmes...\n";
        
        // On utilise une transaction pour s'assurer que tout est inséré correctement.
        $this->pdo->beginTransaction();
        try {
            // Insertion d'une matière
            $this->pdo->exec("INSERT INTO subjects (name, description) VALUES ('Mathématiques', 'Quiz sur les concepts mathématiques de base.')");
            $mathSubjectId = $this->pdo->lastInsertId();

            // Insertion de thèmes pour cette matière
            $this->pdo->exec("INSERT INTO topics (subject_id, name) VALUES ($mathSubjectId, 'Calcul')");
            $this->pdo->exec("INSERT INTO topics (subject_id, name) VALUES ($mathSubjectId, 'Géométrie')");

            $this->pdo->commit();
        } catch (Exception $e) {
            $this->pdo->rollBack();
            echo "Erreur lors du seeding des matières : " . $e->getMessage() . "\n";
        }
    }

    private function seedQuizzesAndQuestions(): void
    {
        echo "  - Seeding des quiz et des questions...\n";

        $this->pdo->beginTransaction();
        try {
            // On récupère l'ID du thème 'Calcul' qu'on a créé
            $stmt = $this->pdo->query("SELECT id FROM topics WHERE name = 'Calcul' LIMIT 1");
            $calculTopicId = $stmt->fetchColumn();

            if ($calculTopicId) {
                // Création d'un quiz
                $this->pdo->exec("INSERT INTO quizzes (topic_id, title, description) VALUES ($calculTopicId, 'Additions Faciles', 'Testez vos compétences en addition.')");
                $quizId = $this->pdo->lastInsertId();

                // Ajout de questions à ce quiz
                // Question 1
                $this->pdo->exec("INSERT INTO questions (quiz_id, text) VALUES ($quizId, 'Que font 2 + 2 ?')");
                $question1Id = $this->pdo->lastInsertId();
                $this->pdo->exec("INSERT INTO options (question_id, text, is_correct) VALUES ($question1Id, '3', 0)");
                $this->pdo->exec("INSERT INTO options (question_id, text, is_correct) VALUES ($question1Id, '4', 1)");
                $this->pdo->exec("INSERT INTO options (question_id, text, is_correct) VALUES ($question1Id, '5', 0)");

                // Question 2
                $this->pdo->exec("INSERT INTO questions (quiz_id, text) VALUES ($quizId, 'Que font 5 + 3 ?')");
                $question2Id = $this->pdo->lastInsertId();
                $this->pdo->exec("INSERT INTO options (question_id, text, is_correct) VALUES ($question2Id, '8', 1)");
                $this->pdo->exec("INSERT INTO options (question_id, text, is_correct) VALUES ($question2Id, '7', 0)");
                $this->pdo->exec("INSERT INTO options (question_id, text, is_correct) VALUES ($question2Id, '9', 0)");
            }

            $this->pdo->commit();
        } catch (Exception $e) {
            $this->pdo->rollBack();
            echo "Erreur lors du seeding des quiz : " . $e->getMessage() . "\n";
        }
    }
}

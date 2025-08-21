<?php

namespace App\Controllers;

use App\Shared\Database;
use PDO;

class SubjectsController extends BaseController
{
    private $db;

    public function __construct()
    {
        parent::__construct();
        $this->db = Database::getInstance()->getConnection();
    }

    public function index()
    {
        try {
            // Fetch all subjects with their themes
            $subjects = $this->getSubjectsWithThemes();
            
            // Render the view
            $this->render('subjects/index', [
                'subjects' => $subjects,
                'page_title' => 'Matières - Askiaverse'
            ]);
        } catch (\Exception $e) {
            // Handle error
            $this->render('error', [
                'message' => 'Erreur lors du chargement des matières: ' . $e->getMessage(),
                'page_title' => 'Erreur - Askiaverse'
            ]);
        }
    }

    public function show($subjectId)
    {
        try {
            // Fetch subject details
            $subject = $this->getSubjectById($subjectId);
            
            if (!$subject) {
                $this->render('error', [
                    'message' => 'Matière non trouvée',
                    'page_title' => 'Erreur - Askiaverse'
                ]);
                return;
            }

            // Fetch themes for this subject
            $themes = $this->getThemesBySubjectId($subjectId);
            
            // Fetch sample questions for this subject
            $questions = $this->getSampleQuestionsBySubjectId($subjectId);

            $this->render('subjects/show', [
                'subject' => $subject,
                'themes' => $themes,
                'questions' => $questions,
                'page_title' => $subject['name'] . ' - Askiaverse'
            ]);
        } catch (\Exception $e) {
            $this->render('error', [
                'message' => 'Erreur lors du chargement de la matière: ' . $e->getMessage(),
                'page_title' => 'Erreur - Askiaverse'
            ]);
        }
    }

    private function getSubjectsWithThemes()
    {
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
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    private function getSubjectById($id)
    {
        $sql = "SELECT * FROM subjects WHERE id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    private function getThemesBySubjectId($subjectId)
    {
        $sql = "SELECT * FROM themes WHERE subject_id = ? ORDER BY difficulty, name";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$subjectId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    private function getSampleQuestionsBySubjectId($subjectId)
    {
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
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$subjectId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}

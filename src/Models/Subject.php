<?php
namespace Models;

use Shared\Database;

class Subject {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance();
    }

    public function getAll() {
        $sql = "SELECT * FROM subjects ORDER BY name ASC";
        return $this->db->fetchAll($sql);
    }

    public function getById($id) {
        $sql = "SELECT * FROM subjects WHERE id = :id LIMIT 1";
        return $this->db->fetch($sql, ['id' => $id]);
    }

    public function getByName($name) {
        $sql = "SELECT * FROM subjects WHERE name = :name LIMIT 1";
        return $this->db->fetch($sql, ['name' => $name]);
    }

    public function getThemes($subjectId) {
        $sql = "SELECT * FROM themes WHERE subject_id = :subject_id ORDER BY name ASC";
        return $this->db->fetchAll($sql, ['subject_id' => $subjectId]);
    }

    public function getThemeById($themeId) {
        $sql = "SELECT * FROM themes WHERE id = :id LIMIT 1";
        return $this->db->fetch($sql, ['id' => $themeId]);
    }

    public function getQuestionsByTheme($themeId, $limit = 10) {
        $sql = "SELECT * FROM questions WHERE theme_id = :theme_id ORDER BY RAND() LIMIT :limit";
        return $this->db->fetchAll($sql, ['theme_id' => $themeId, 'limit' => $limit]);
    }

    public function getQuestionById($questionId) {
        $sql = "SELECT * FROM questions WHERE id = :id LIMIT 1";
        return $this->db->fetch($sql, ['id' => $questionId]);
    }

    public function getRandomQuestions($subjectId, $themeId = null, $limit = 10) {
        if ($themeId) {
            $sql = "SELECT * FROM questions WHERE theme_id = :theme_id ORDER BY RAND() LIMIT :limit";
            $params = ['theme_id' => $themeId, 'limit' => $limit];
        } else {
            $sql = "SELECT q.* FROM questions q 
                    JOIN themes t ON q.theme_id = t.id 
                    WHERE t.subject_id = :subject_id 
                    ORDER BY RAND() LIMIT :limit";
            $params = ['subject_id' => $subjectId, 'limit' => $limit];
        }
        
        return $this->db->fetchAll($sql, $params);
    }

    public function getQuestionCount($subjectId = null, $themeId = null) {
        if ($themeId) {
            $sql = "SELECT COUNT(*) as count FROM questions WHERE theme_id = :theme_id";
            $params = ['theme_id' => $themeId];
        } elseif ($subjectId) {
            $sql = "SELECT COUNT(*) as count FROM questions q 
                    JOIN themes t ON q.theme_id = t.id 
                    WHERE t.subject_id = :subject_id";
            $params = ['subject_id' => $subjectId];
        } else {
            $sql = "SELECT COUNT(*) as count FROM questions";
            $params = [];
        }
        
        $result = $this->db->fetch($sql, $params);
        return $result['count'];
    }
} 
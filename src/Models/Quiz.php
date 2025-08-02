<?php
namespace Models;

use Shared\Database;

class Quiz {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance();
    }

    public function saveAttempt($data) {
        $data['created_at'] = date('Y-m-d H:i:s');
        return $this->db->insert('quiz_attempts', $data);
    }

    public function getAttemptById($id) {
        $sql = "SELECT * FROM quiz_attempts WHERE id = :id LIMIT 1";
        return $this->db->fetch($sql, ['id' => $id]);
    }

    public function getUserAttempts($userId, $limit = 20) {
        $sql = "SELECT qa.*, s.name as subject_name, t.name as theme_name 
                FROM quiz_attempts qa
                LEFT JOIN themes t ON qa.theme_id = t.id
                LEFT JOIN subjects s ON t.subject_id = s.id
                WHERE qa.user_id = :user_id 
                ORDER BY qa.created_at DESC 
                LIMIT :limit";
        
        return $this->db->fetchAll($sql, ['user_id' => $userId, 'limit' => $limit]);
    }

    public function getUserStats($userId) {
        $sql = "SELECT 
                    COUNT(*) as total_attempts,
                    AVG(score) as average_score,
                    MAX(score) as best_score,
                    MIN(score) as worst_score,
                    SUM(time_spent) as total_time,
                    COUNT(CASE WHEN score >= 80 THEN 1 END) as excellent_count,
                    COUNT(CASE WHEN score >= 60 AND score < 80 THEN 1 END) as good_count,
                    COUNT(CASE WHEN score < 60 THEN 1 END) as needs_improvement_count
                FROM quiz_attempts 
                WHERE user_id = :user_id";
        
        return $this->db->fetch($sql, ['user_id' => $userId]);
    }

    public function getLeaderboard($limit = 10) {
        $sql = "SELECT 
                    u.username,
                    u.school,
                    u.state,
                    COUNT(qa.id) as total_quizzes,
                    AVG(qa.score) as average_score,
                    MAX(qa.score) as best_score,
                    SUM(qa.time_spent) as total_time
                FROM users u
                LEFT JOIN quiz_attempts qa ON u.id = qa.user_id
                GROUP BY u.id, u.username, u.school, u.state
                HAVING total_quizzes > 0
                ORDER BY average_score DESC, total_quizzes DESC
                LIMIT :limit";
        
        return $this->db->fetchAll($sql, ['limit' => $limit]);
    }

    public function getSchoolLeaderboard($school, $limit = 10) {
        $sql = "SELECT 
                    u.username,
                    u.school,
                    u.state,
                    COUNT(qa.id) as total_quizzes,
                    AVG(qa.score) as average_score,
                    MAX(qa.score) as best_score
                FROM users u
                LEFT JOIN quiz_attempts qa ON u.id = qa.user_id
                WHERE u.school = :school
                GROUP BY u.id, u.username, u.school, u.state
                HAVING total_quizzes > 0
                ORDER BY average_score DESC, total_quizzes DESC
                LIMIT :limit";
        
        return $this->db->fetchAll($sql, ['school' => $school, 'limit' => $limit]);
    }

    public function getSubjectStats($subjectId) {
        $sql = "SELECT 
                    COUNT(qa.id) as total_attempts,
                    AVG(qa.score) as average_score,
                    COUNT(DISTINCT qa.user_id) as unique_users
                FROM quiz_attempts qa
                JOIN themes t ON qa.theme_id = t.id
                WHERE t.subject_id = :subject_id";
        
        return $this->db->fetch($sql, ['subject_id' => $subjectId]);
    }

    public function getRecentAttempts($limit = 10) {
        $sql = "SELECT 
                    qa.*,
                    u.username,
                    s.name as subject_name,
                    t.name as theme_name
                FROM quiz_attempts qa
                JOIN users u ON qa.user_id = u.id
                LEFT JOIN themes t ON qa.theme_id = t.id
                LEFT JOIN subjects s ON t.subject_id = s.id
                ORDER BY qa.created_at DESC
                LIMIT :limit";
        
        return $this->db->fetchAll($sql, ['limit' => $limit]);
    }

    public function getDailyStats($userId, $days = 7) {
        $sql = "SELECT 
                    DATE(created_at) as date,
                    COUNT(*) as attempts,
                    AVG(score) as average_score,
                    MAX(score) as best_score
                FROM quiz_attempts 
                WHERE user_id = :user_id 
                AND created_at >= DATE_SUB(NOW(), INTERVAL :days DAY)
                GROUP BY DATE(created_at)
                ORDER BY date DESC";
        
        return $this->db->fetchAll($sql, ['user_id' => $userId, 'days' => $days]);
    }
} 
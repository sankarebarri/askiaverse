<?php
namespace App\Models;
use Shared\Database;

class User {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance();
    }

    public function authenticate($username, $password) {
        $sql = "SELECT * FROM users WHERE username = :username LIMIT 1";
        $user = $this->db->fetch($sql, ['username' => $username]);

        if ($user && password_verify($password, $user['password'])) {
            // Remove password from user data
            unset($user['password']);
            return $user;
        }

        return false;
    }

    public function getById($id) {
        $sql = "SELECT id, username, email, school, state, class_level, created_at, last_login FROM users WHERE id = :id LIMIT 1";
        return $this->db->fetch($sql, ['id' => $id]);
    }

    public function getByUsername($username) {
        $sql = "SELECT id, username, email, school, state, class_level, created_at, last_login FROM users WHERE username = :username LIMIT 1";
        return $this->db->fetch($sql, ['username' => $username]);
    }

    public function create($data) {
        // Hash password
        $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);
        $data['created_at'] = date('Y-m-d H:i:s');
        
        return $this->db->insert('users', $data);
    }

    public function updateLastLogin($userId) {
        $sql = "UPDATE users SET last_login = NOW() WHERE id = :id";
        return $this->db->query($sql, ['id' => $userId]);
    }

    public function updateProfile($userId, $data) {
        return $this->db->update('users', $data, 'id = :id', ['id' => $userId]);
    }

    public function exists($username) {
        $sql = "SELECT COUNT(*) as count FROM users WHERE username = :username";
        $result = $this->db->fetch($sql, ['username' => $username]);
        return $result['count'] > 0;
    }

    public function getStats($userId) {
        // Get user statistics
        $sql = "SELECT 
                    COUNT(*) as total_quizzes,
                    AVG(score) as average_score,
                    MAX(score) as best_score,
                    SUM(time_spent) as total_time
                FROM quiz_attempts 
                WHERE user_id = :user_id";
        
        return $this->db->fetch($sql, ['user_id' => $userId]);
    }
} 
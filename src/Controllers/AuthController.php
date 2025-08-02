<?php
namespace Controllers;

use Models\User;
use Shared\BaseController;

class AuthController extends BaseController {
    private $userModel;

    public function __construct() {
        parent::__construct();
        $this->userModel = new User();
    }

    public function login() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $username = $_POST['username'] ?? '';
            $password = $_POST['password'] ?? '';

            if (empty($username) || empty($password)) {
                $this->setError('Veuillez remplir tous les champs');
                return $this->redirect('?page=login');
            }

            $user = $this->userModel->authenticate($username, $password);

            if ($user) {
                // Start session and store user data
                session_start();
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['username'] = $user['username'];
                $_SESSION['school'] = $user['school'];
                $_SESSION['state'] = $user['state'];
                $_SESSION['class_level'] = $user['class_level'];

                // Update last login
                $this->userModel->updateLastLogin($user['id']);

                $this->setSuccess('Connexion réussie! Bienvenue ' . $user['username']);
                return $this->redirect('?page=dashboard');
            } else {
                $this->setError('Nom d\'utilisateur ou mot de passe incorrect');
                return $this->redirect('?page=login');
            }
        }

        // Show login form
        $this->render('auth/login');
    }

    public function register() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $username = $_POST['username'] ?? '';
            $password = $_POST['password'] ?? '';
            $confirmPassword = $_POST['confirm_password'] ?? '';
            $classLevel = $_POST['class_level'] ?? '';
            $city = $_POST['city'] ?? '';
            $school = $_POST['school'] ?? '';

            // Validation
            if (empty($username) || empty($password) || empty($confirmPassword)) {
                $this->setError('Veuillez remplir tous les champs obligatoires');
                return $this->redirect('?page=register');
            }

            if ($password !== $confirmPassword) {
                $this->setError('Les mots de passe ne correspondent pas');
                return $this->redirect('?page=register');
            }

            if (strlen($password) < 6) {
                $this->setError('Le mot de passe doit contenir au moins 6 caractères');
                return $this->redirect('?page=register');
            }

            if ($this->userModel->exists($username)) {
                $this->setError('Ce nom d\'utilisateur existe déjà');
                return $this->redirect('?page=register');
            }

            // Create user
            $userData = [
                'username' => $username,
                'password' => $password,
                'class_level' => $classLevel,
                'city' => $city,
                'school' => $school,
                'state' => 'Gao', // Default state as requested
                'email' => $username . '@askiaverse.local' // Generate email
            ];

            try {
                $userId = $this->userModel->create($userData);
                
                if ($userId) {
                    $this->setSuccess('Compte créé avec succès! Vous pouvez maintenant vous connecter');
                    return $this->redirect('?page=login');
                } else {
                    $this->setError('Erreur lors de la création du compte');
                    return $this->redirect('?page=register');
                }
            } catch (\Exception $e) {
                $this->setError('Erreur: ' . $e->getMessage());
                return $this->redirect('?page=register');
            }
        }

        // Show registration form
        $this->render('auth/register');
    }

    public function logout() {
        session_start();
        session_destroy();
        
        $this->setSuccess('Déconnexion réussie');
        return $this->redirect('?page=index');
    }

    public function profile() {
        $this->requireAuth();
        
        $user = $this->userModel->getById($_SESSION['user_id']);
        $stats = $this->userModel->getStats($_SESSION['user_id']);
        
        $this->render('profile', [
            'user' => $user,
            'stats' => $stats
        ]);
    }

    public function updateProfile() {
        $this->requireAuth();
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $classLevel = $_POST['class_level'] ?? '';
            $city = $_POST['city'] ?? '';
            $school = $_POST['school'] ?? '';

            $updateData = [
                'class_level' => $classLevel,
                'city' => $city,
                'school' => $school
            ];

            try {
                $this->userModel->updateProfile($_SESSION['user_id'], $updateData);
                
                // Update session data
                $_SESSION['class_level'] = $classLevel;
                $_SESSION['school'] = $school;
                
                $this->setSuccess('Profil mis à jour avec succès');
            } catch (\Exception $e) {
                $this->setError('Erreur lors de la mise à jour: ' . $e->getMessage());
            }
        }

        return $this->redirect('?page=profile');
    }

    private function requireAuth() {
        session_start();
        if (!isset($_SESSION['user_id'])) {
            $this->setError('Veuillez vous connecter pour accéder à cette page');
            return $this->redirect('?page=login');
        }
    }
} 
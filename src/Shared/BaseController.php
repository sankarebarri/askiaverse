<?php

// ===================================================================
// FICHIER : src/Shared/BaseController.php
// C'est un contrôleur de base que nos autres contrôleurs étendront.
// Il fournit des fonctions utiles et réutilisables.
// ===================================================================
namespace App\Shared;
class BaseController
{
    /**
     * Envoie une réponse JSON au client.
     *
     * @param array $data Les données à encoder en JSON.
     * @param int $statusCode Le code de statut HTTP à envoyer.
     * @return void
     */
    protected function jsonResponse(array $data, int $statusCode = 200): void
    {
        // Définit l'en-tête Content-Type pour indiquer une réponse JSON.
        header('Content-Type: application/json; charset=utf-8');
        
        // Définit le code de réponse HTTP.
        http_response_code($statusCode);
        
        // Encode le tableau de données en une chaîne JSON et l'affiche.
        echo json_encode($data);
        
        // Termine le script pour empêcher toute sortie supplémentaire.
        exit;
    }

    /**
     * Rend une vue PHP.
     *
     * @param string $view Le nom de la vue à rendre.
     * @param array $data Les données à passer à la vue.
     * @return void
     */
    protected function render(string $view, array $data = []): void
    {
        // Définit l'en-tête Content-Type pour indiquer une page HTML.
        header('Content-Type: text/html; charset=utf-8');
        
        // Extrait les variables pour qu'elles soient disponibles dans la vue.
        extract($data);
        
        // Inclut la vue.
        $viewPath = __DIR__ . '/../../resources/views/' . $view . '.php';
        if (file_exists($viewPath)) {
            require $viewPath;
        } else {
            throw new \Exception("Vue non trouvée: {$view}");
        }
    }

    protected function renderWithLayout(string $view, array $data = [], string $layout = 'admin'): void
    {
        // Définit l'en-tête Content-Type pour indiquer une page HTML.
        header('Content-Type: text/html; charset=utf-8');
        
        // Start output buffering
        ob_start();
        
        // Include the view file
        $viewPath = __DIR__ . '/../../resources/views/' . $view . '.php';
        if (file_exists($viewPath)) {
            require $viewPath;
        } else {
            throw new \Exception("Vue non trouvée: {$view}");
        }
        
        // Get the content
        $content = ob_get_clean();
        
        // Set layout data
        $layoutData = array_merge($data, [
            'content' => $content,
            'current_page' => $data['current_page'] ?? 'dashboard'
        ]);
        
        // Render the layout
        $layoutPath = __DIR__ . '/../../resources/views/layouts/' . $layout . '.php';
        if (file_exists($layoutPath)) {
            extract($layoutData);
            require $layoutPath;
        } else {
            throw new \Exception("Layout non trouvé: {$layout}");
        }
    }

    protected function renderPublicWithLayout(string $view, array $data = [], array $userStats = []): void
    {
        // Définit l'en-tête Content-Type pour indiquer une page HTML.
        header('Content-Type: text/html; charset=utf-8');
        
        // Start output buffering
        ob_start();
        
        // Extract variables for the view
        extract($data);
        
        // Include the view file
        $viewPath = __DIR__ . '/../../resources/views/' . $view . '.php';
        if (file_exists($viewPath)) {
            require $viewPath;
        } else {
            throw new \Exception("Vue non trouvée: {$view}");
        }
        
        // Get the content
        $content = ob_get_clean();
        
        // Set layout data
        $layoutData = array_merge($data, [
            'content' => $content,
            'user_stats' => $userStats,
            'current_page' => $data['current_page'] ?? 'dashboard'
        ]);
        
        // Render the public layout
        $layoutPath = __DIR__ . '/../../resources/views/layouts/public.php';
        if (file_exists($layoutPath)) {
            extract($layoutData);
            require $layoutPath;
        } else {
            throw new \Exception("Layout public non trouvé");
        }
    }
}


// ===================================================================
// FICHIER : src/Auth/AuthController.php
// Ce contrôleur gère toute la logique d'authentification des utilisateurs
// comme l'inscription et la connexion.
// ===================================================================

// Nous devons inclure le BaseController car AuthController l'étend.
require_once __DIR__ . '/../Shared/BaseController.php';

class AuthController extends BaseController
{
    private PDO $pdo;

    /**
     * Constructeur de AuthController.
     *
     * @param PDO $pdo L'objet de connexion à la base de données.
     */
    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    /**
     * Gère le processus d'inscription de l'utilisateur.
     *
     * @return void
     */
    public function register(): void
    {
        // 1. Récupère les données POST brutes de la requête.
        // Nous nous attendons à ce que le frontend envoie des données JSON.
        $input = (array) json_decode(file_get_contents('php://input'), true);

        // 2. Validation de base : Vérifie si le nom d'utilisateur et le mot de passe existent.
        if (empty($input['username']) || empty($input['password'])) {
            $this->jsonResponse(['message' => 'Le nom d\'utilisateur et le mot de passe sont requis.'], 400); // 400 Bad Request
            return;
        }
        
        $username = trim($input['username']);
        $password = $input['password'];
        
        // D'autres validations pourraient être ajoutées ici (ex: longueur du mot de passe, format du nom d'utilisateur).

        // 3. Vérifie si le nom d'utilisateur existe déjà dans la base de données.
        $stmt = $this->pdo->prepare("SELECT id FROM users WHERE username = ? AND deleted_at IS NULL");
        $stmt->execute([$username]);
        if ($stmt->fetch()) {
            $this->jsonResponse(['message' => 'Ce nom d\'utilisateur est déjà pris.'], 409); // 409 Conflict
            return;
        }

        // 4. Hache le mot de passe de manière sécurisée.
        // BCRYPT est l'algorithme de hachage standard actuel, robuste et par défaut.
        $passwordHash = password_hash($password, PASSWORD_BCRYPT);

        // 5. Insère le nouvel utilisateur dans la base de données.
        try {
            $stmt = $this->pdo->prepare(
                "INSERT INTO users (username, password_hash, country_code) VALUES (?, ?, ?)"
            );
            
            // Nous attribuons par défaut le country_code à 'ML' comme discuté.
            $stmt->execute([$username, $passwordHash, 'ML']);
            
            $newUserId = $this->pdo->lastInsertId();

            // 6. Envoie une réponse de succès.
            $this->jsonResponse([
                'message' => 'Utilisateur enregistré avec succès.',
                'userId' => $newUserId
            ], 201); // 201 Created

        } catch (PDOException $e) {
            // Dans une application réelle, vous devriez logger cette erreur.
            $this->jsonResponse(['message' => 'Une erreur est survenue lors de l\'inscription.'], 500); // 500 Internal Server Error
        }
    }
    
    /**
     * Gère le processus de connexion de l'utilisateur.
     *
     * @return void
     */
    public function login(): void
    {
        // 1. Récupère les données JSON brutes de la requête.
        $input = (array) json_decode(file_get_contents('php://input'), true);

        // 2. Validation de base.
        if (empty($input['username']) || empty($input['password'])) {
            $this->jsonResponse(['message' => 'Le nom d\'utilisateur et le mot de passe sont requis.'], 400);
            return;
        }

        $username = trim($input['username']);
        $password = $input['password'];

        // 3. Trouve l'utilisateur dans la base de données.
        // On sélectionne aussi le hash du mot de passe pour le vérifier.
        $stmt = $this->pdo->prepare("SELECT id, username, password_hash, status FROM users WHERE username = ? AND deleted_at IS NULL");
        $stmt->execute([$username]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        // 4. Vérifie si l'utilisateur existe ET si le mot de passe est correct.
        if (!$user || !password_verify($password, $user['password_hash'])) {
            $this->jsonResponse(['message' => 'Nom d\'utilisateur ou mot de passe incorrect.'], 401); // 401 Unauthorized
            return;
        }
        
        // 5. Vérifie si le compte de l'utilisateur est actif.
        if ($user['status'] !== 'active') {
            $this->jsonResponse(['message' => 'Votre compte n\'est pas actif. Veuillez contacter le support.'], 403); // 403 Forbidden
            return;
        }

        // 6. Connexion réussie : On démarre une session.
        // Il est crucial de régénérer l'ID de session pour prévenir les attaques de fixation de session.
        session_regenerate_id(true);
        
        // On stocke les informations de l'utilisateur dans la session.
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];

        // 7. Envoie une réponse de succès.
        $this->jsonResponse([
            'message' => 'Connexion réussie.',
            'user' => [
                'id' => $user['id'],
                'username' => $user['username']
            ]
        ]);
    }
}

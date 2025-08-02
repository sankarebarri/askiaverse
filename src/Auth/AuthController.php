<?php

// ===================================================================
// FICHIER : src/Controllers/AuthController.php
// NOTE : Le chemin et le namespace ont été mis à jour pour correspondre à votre structure.
// ===================================================================
namespace App\Controllers; // MODIFIÉ : Doit correspondre au chemin du dossier (src -> App, Controllers -> Controllers)

use App\Shared\BaseController;
use PDO;

class AuthController extends BaseController
{
    private PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    /**
     * Gère le processus d'inscription de l'utilisateur.
     * @return void
     */
    public function register(): void
    {
        // 1. Récupère les données POST brutes de la requête.
        $input = (array) json_decode(file_get_contents('php://input'), true);

        // 2. Validation de base.
        if (empty($input['username']) || empty($input['password'])) {
            $this->jsonResponse(['message' => 'Le nom d\'utilisateur et le mot de passe sont requis.'], 400);
            return;
        }
        
        $username = trim($input['username']);
        $password = $input['password'];

        // 3. Vérifie si le nom d'utilisateur existe déjà.
        $stmt = $this->pdo->prepare("SELECT id FROM users WHERE username = ? AND deleted_at IS NULL");
        $stmt->execute([$username]);
        if ($stmt->fetch()) {
            $this->jsonResponse(['message' => 'Ce nom d\'utilisateur est déjà pris.'], 409);
            return;
        }

        // 4. Hache le mot de passe de manière sécurisée.
        $passwordHash = password_hash($password, PASSWORD_BCRYPT);

        // 5. Insère le nouvel utilisateur dans la base de données.
        try {
            $stmt = $this->pdo->prepare(
                "INSERT INTO users (username, password_hash, country_code) VALUES (?, ?, ?)"
            );
            $stmt->execute([$username, $passwordHash, 'ML']); // Default country_code 'ML'
            
            $newUserId = $this->pdo->lastInsertId();

            // 6. Envoie une réponse de succès.
            $this->jsonResponse([
                'message' => 'Utilisateur enregistré avec succès.',
                'userId' => $newUserId
            ], 201);

        } catch (\PDOException $e) {
            $this->jsonResponse(['message' => 'Une erreur est survenue lors de l\'inscription.'], 500);
        }
    }

    /**
     * Gère le processus de connexion de l'utilisateur.
     * @return void
     */
    public function login(): void
    {
        // 1. Récupère les données JSON brutes.
        $input = (array) json_decode(file_get_contents('php://input'), true);

        // 2. Validation de base.
        if (empty($input['username']) || empty($input['password'])) {
            $this->jsonResponse(['message' => 'Le nom d\'utilisateur et le mot de passe sont requis.'], 400);
            return;
        }

        $username = trim($input['username']);
        $password = $input['password'];

        // 3. Trouve l'utilisateur dans la base de données.
        $stmt = $this->pdo->prepare("SELECT id, username, password_hash, status FROM users WHERE username = ? AND deleted_at IS NULL");
        $stmt->execute([$username]);
        $user = $stmt->fetch(\PDO::FETCH_ASSOC);

        // 4. Vérifie si l'utilisateur existe ET si le mot de passe est correct.
        if (!$user || !password_verify($password, $user['password_hash'])) {
            $this->jsonResponse(['message' => 'Nom d\'utilisateur ou mot de passe incorrect.'], 401);
            return;
        }
        
        // 5. Vérifie si le compte est actif.
        if ($user['status'] !== 'active') {
            $this->jsonResponse(['message' => 'Votre compte n\'est pas actif. Veuillez contacter le support.'], 403);
            return;
        }

        // 6. Connexion réussie : On démarre ou régénère la session.
        session_regenerate_id(true);
        
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

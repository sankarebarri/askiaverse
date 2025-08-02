<?php
// FICHIER : config/routes.php
// C'est ici que nous définirons toutes les routes de notre application.
// ===================================================================

// On crée une nouvelle instance de notre routeur.
$router = new Router();

// Définition des routes.
// Format : $router->get('URI', 'NomDuContrôleur', 'nomDeLaMéthode');

// Routes pour l'authentification
$router->post('/register', 'AuthController', 'register');
$router->post('/login', 'AuthController', 'login');

// Route pour la liste des quiz
$router->get('/quizzes', 'QuizController', 'index'); // <-- AJOUTER CETTE LIGNE

// Plus tard, nous ajouterons des routes pour afficher les pages de formulaire,
// le tableau de bord, etc.

// On retourne l'objet routeur pour qu'il puisse être utilisé dans notre point d'entrée.
return $router;


<?php
// FICHIER : config/routes.php
// C'est ici que nous définirons toutes les routes de notre application.
// ===================================================================

// Importer la classe Router
use App\Shared\Router;

// On crée une nouvelle instance de notre routeur.
$router = new Router();

// Définition des routes.
// Format : $router->get('URI', 'NomDuContrôleur', 'nomDeLaMéthode');

// Route pour la page d'accueil
$router->get('/', 'HomeController', 'index');

// Routes pour l'authentification
$router->get('/login', 'AuthController', 'showLogin');
$router->get('/register', 'AuthController', 'showRegister');
$router->post('/register', 'AuthController', 'register');
$router->post('/login', 'AuthController', 'login');
$router->get('/logout', 'AuthController', 'logout');

// Route pour le quiz avec paramètres
$router->get('/quiz', 'QuizController', 'showQuiz');

// API routes for quiz functionality
$router->get('/api/quiz/questions', 'QuizController', 'getQuestions');
$router->post('/api/quiz/submit', 'QuizController', 'submitResult');

// Route pour le tableau de bord
$router->get('/dashboard', 'DashboardController', 'index');

// Routes pour les autres pages
$router->get('/competition', 'CompetitionController', 'index');
$router->get('/community', 'CommunityController', 'index');

// Plus tard, nous ajouterons des routes pour afficher les pages de formulaire,
// le tableau de bord, etc.

// On retourne l'objet routeur pour qu'il puisse être utilisé dans notre point d'entrée.
return $router;


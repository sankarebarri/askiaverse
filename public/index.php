<?php
// Set session directory to a writable location
ini_set('session.save_path', '/tmp');
session_start();
?>
<!DOCTYPE html>
<html lang="fr" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Askiaverse - L'aventure du savoir commence ici</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gradient-to-br from-blue-900 via-purple-900 to-indigo-900 min-h-screen">
    <!-- Header -->
    <?php include 'components/header.php'; ?>

    <!-- Hero Section -->
    <main class="flex-1">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-20">
            <div class="text-center">
                <!-- Main Logo -->
                <div class="mx-auto h-32 w-32 bg-white rounded-full flex items-center justify-center mb-8">
                    <span class="text-6xl">🚀</span>
                </div>
                
                <!-- Main Title -->
                <h1 class="text-5xl md:text-6xl font-bold text-white mb-6">
                    Askiaverse
                </h1>
                
                <!-- Tagline -->
                <p class="text-xl md:text-2xl text-blue-200 mb-8 max-w-3xl mx-auto">
                    L'aventure du savoir commence ici. Plongez dans un univers de jeux éducatifs conçus pour les jeunes esprits brillants du Mali.
                </p>
                
                <!-- Call to Action -->
                <div class="space-y-4">
                    <?php if (isset($_SESSION['user_id'])): ?>
                        <a href="simple-subjects.php" class="inline-block bg-blue-600 text-white px-8 py-4 rounded-lg text-xl font-bold hover:bg-blue-700 transition-colors duration-200">
                            🎯 Continuer l'Apprentissage
                        </a>
                        <br>
                        <a href="user-dashboard.php" class="inline-block bg-green-600 text-white px-6 py-3 rounded-lg text-lg font-medium hover:bg-green-700 transition-colors duration-200">
                            📊 Mon Tableau de Bord
                        </a>
                    <?php else: ?>
                        <a href="simple-subjects.php" class="inline-block bg-blue-600 text-white px-8 py-4 rounded-lg text-xl font-bold hover:bg-blue-700 transition-colors duration-200">
                            🚀 Essayer un Défi Gratuit!
                        </a>
                        <br>
                        <a href="register.php" class="inline-block bg-green-600 text-white px-6 py-3 rounded-lg text-lg font-medium hover:bg-green-700 transition-colors duration-200">
                            ✨ Créer un Compte
                        </a>
                    <?php endif; ?>
                </div>
            </div>
            
            <!-- Features Grid -->
            <div class="mt-20 grid grid-cols-1 md:grid-cols-3 gap-8">
                <div class="text-center">
                    <div class="text-4xl mb-4">📚</div>
                    <h3 class="text-xl font-bold text-white mb-2">Matières Variées</h3>
                    <p class="text-blue-200">Mathématiques, Français, Sciences, Histoire, Géographie et plus encore</p>
                </div>
                <div class="text-center">
                    <div class="text-4xl mb-4">🎮</div>
                    <h3 class="text-xl font-bold text-white mb-2">Quiz Interactifs</h3>
                    <p class="text-blue-200">Apprenez en vous amusant avec nos quiz dynamiques et chronométrés</p>
                </div>
                <div class="text-center">
                    <div class="text-4xl mb-4">🏆</div>
                    <h3 class="text-xl font-bold text-white mb-2">Système de Récompenses</h3>
                    <p class="text-blue-200">Gagnez de l'XP et des Orbs en progressant dans vos études</p>
                </div>
            </div>
        </div>
    </main>

    <!-- Footer -->
    <footer class="bg-white/10 backdrop-blur-sm border-t border-white/20 mt-20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <div class="text-center text-blue-200">
                <p>&copy; 2025 Askiaverse. Tous droits réservés.</p>
                <p class="mt-2">Éducation interactive pour les jeunes esprits brillants du Mali</p>
            </div>
        </div>
    </footer>
</body>
</html>


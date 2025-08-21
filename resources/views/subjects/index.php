<?php
$page_title = 'Matières - Askiaverse';
?>
<!DOCTYPE html>
<html lang="fr" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $page_title ?></title>
    <link rel="stylesheet" href="/assets/app.css">
</head>
<body class="bg-gray-50">
    <!-- Header -->
    <header class="bg-white shadow-sm border-b">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center py-4">
                <div class="flex items-center">
                    <h1 class="text-2xl font-bold text-gray-900">Askiaverse</h1>
                </div>
                <nav class="flex space-x-4">
                    <a href="/" class="text-gray-600 hover:text-gray-900">Accueil</a>
                    <a href="/subjects" class="text-blue-600 font-medium">Matières</a>
                    <a href="/login" class="text-gray-600 hover:text-gray-900">Se Connecter</a>
                    <a href="/register" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700">Créer un Compte</a>
                </nav>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Page Header -->
        <div class="text-center mb-12">
            <h1 class="text-4xl font-bold text-gray-900 mb-4">
                Découvrez nos matières
            </h1>
            <p class="text-xl text-gray-600 max-w-3xl mx-auto">
                Plongez dans un univers d'apprentissage riche et varié, conçu spécialement pour les jeunes esprits brillants du Mali.
            </p>
        </div>

        <!-- Subjects Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            <?php foreach ($subjects as $subject): ?>
            <div class="bg-white rounded-xl shadow-lg overflow-hidden hover:shadow-xl transition-shadow duration-300">
                <!-- Subject Header -->
                <div class="p-6" style="background: linear-gradient(135deg, <?= $subject['color'] ?>20, <?= $subject['color'] ?>10);">
                    <div class="flex items-center justify-between mb-4">
                        <div class="text-4xl"><?= $subject['icon'] ?></div>
                        <div class="text-sm bg-white px-3 py-1 rounded-full text-gray-600 font-medium">
                            <?= $subject['theme_count'] ?> thèmes
                        </div>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-900 mb-2"><?= htmlspecialchars($subject['name']) ?></h3>
                    <p class="text-gray-600"><?= htmlspecialchars($subject['description']) ?></p>
                </div>
                
                <!-- Subject Actions -->
                <div class="p-6 bg-gray-50">
                    <a href="/subjects/<?= $subject['id'] ?>" 
                       class="w-full bg-white border-2 border-gray-200 text-gray-700 px-4 py-3 rounded-lg hover:border-gray-300 hover:bg-gray-50 transition-colors duration-200 text-center block font-medium">
                        Explorer les thèmes
                    </a>
                </div>
            </div>
            <?php endforeach; ?>
        </div>

        <!-- Call to Action -->
        <div class="text-center mt-16">
            <div class="bg-gradient-to-r from-blue-600 to-purple-600 rounded-2xl p-8 text-white">
                <h2 class="text-3xl font-bold mb-4">Prêt à commencer l'aventure ?</h2>
                <p class="text-xl mb-6 opacity-90">
                    Rejoignez des milliers d'élèves qui apprennent en s'amusant sur Askiaverse
                </p>
                <a href="/register" class="bg-white text-blue-600 px-8 py-4 rounded-lg text-lg font-bold hover:bg-gray-100 transition-colors duration-200 inline-block">
                    🚀 Commencer maintenant
                </a>
            </div>
        </div>
    </main>

    <!-- Footer -->
    <footer class="bg-gray-900 text-white py-12 mt-20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <p class="text-gray-400">
                © 2024 Askiaverse. L'aventure du savoir commence ici.
            </p>
        </div>
    </footer>
</body>
</html>

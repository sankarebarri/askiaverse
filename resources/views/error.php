<!DOCTYPE html>
<html lang="fr" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $page_title ?? 'Erreur - Askiaverse' ?></title>
    <link rel="stylesheet" href="/assets/app.css">
</head>
<body class="bg-gray-50">
    <!-- Header -->
    <header class="bg-white shadow-sm border-b">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center py-4">
                <div class="flex items-center">
                    <a href="/" class="text-2xl font-bold text-gray-900 hover:text-blue-600">Askiaverse</a>
                </div>
                <nav class="flex space-x-4">
                    <a href="/" class="text-gray-600 hover:text-gray-900">Accueil</a>
                    <a href="/subjects" class="text-gray-600 hover:text-gray-900">Matières</a>
                </nav>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-16 text-center">
        <div class="bg-white rounded-xl shadow-lg p-8">
            <div class="text-6xl mb-6">⚠️</div>
            <h1 class="text-3xl font-bold text-gray-900 mb-4">Une erreur s'est produite</h1>
            <p class="text-xl text-gray-600 mb-8"><?= htmlspecialchars($message ?? 'Une erreur inattendue s\'est produite.') ?></p>
            <div class="flex justify-center space-x-4">
                <a href="/" class="bg-blue-600 text-white px-6 py-3 rounded-lg hover:bg-blue-700 transition-colors duration-200">
                    Retour à l'accueil
                </a>
                <a href="/subjects" class="bg-gray-200 text-gray-700 px-6 py-3 rounded-lg hover:bg-gray-300 transition-colors duration-200">
                    Voir les matières
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

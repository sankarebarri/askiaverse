<?php
// ===================================================================
// FICHIER : resources/views/layouts/app.php
// C'est notre gabarit (template) de page principal.
// Toutes nos autres vues hériteront de cette structure.
// ===================================================================

// On inclut notre fichier d'aide pour pouvoir utiliser la fonction vite_css_tag().
// Le chemin est relatif à notre 'public/index.php' qui inclura ce fichier.
require_once __DIR__ . '/../../../src/Shared/Helpers.php';
?>
<!DOCTYPE html>
<html lang="fr" class="h-full animated-bg">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Askiaverse</title>

    <!-- On utilise notre fonction d'aide pour générer la balise CSS. -->
    <!-- Elle trouvera automatiquement le bon fichier CSS compilé avec le hash. -->
    <?= vite_css_tag('resources/css/app.css') ?>

</head>
<body class="h-full font-sans antialiased">

    <!-- ASKIAGAME COMING SOON Animation -->
    <div class="flex items-center justify-center min-h-screen">
        <div class="text-center">
            <h1 class="text-6xl md:text-8xl font-bold bg-gradient-to-r from-indigo-600 via-purple-600 to-pink-600 bg-clip-text text-transparent animate-glow animate-slide-in-top">
                ASKIAGAME
            </h1>
            <div class="mt-4">
                <h2 class="text-2xl md:text-4xl font-semibold text-gray-700 animate-slide-in-bottom">
                    COMING SOON
                </h2>
            </div>
            <div class="mt-8">
                <div class="flex justify-center space-x-2">
                    <div class="w-3 h-3 bg-indigo-600 rounded-full animate-bounce" style="animation-delay: 0ms;"></div>
                    <div class="w-3 h-3 bg-purple-600 rounded-full animate-bounce" style="animation-delay: 150ms;"></div>
                    <div class="w-3 h-3 bg-pink-600 rounded-full animate-bounce" style="animation-delay: 300ms;"></div>
                </div>
            </div>
            <div class="mt-12">
                <p class="text-lg text-gray-600 max-w-2xl mx-auto leading-relaxed animate-fade-in-up">
                    Une plateforme de jeux révolutionnaire arrive bientôt. 
                    Préparez-vous pour une expérience de gaming unique et immersive.
                </p>
            </div>
        </div>
    </div>

</body>
</html>

<?php
$page_title = $subject['name'] . ' - Askiaverse';
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
                    <a href="/" class="text-2xl font-bold text-gray-900 hover:text-blue-600">Askiaverse</a>
                </div>
                <nav class="flex space-x-4">
                    <a href="/subjects" class="text-blue-600 font-medium">← Retour aux matières</a>
                    <a href="/login" class="text-gray-600 hover:text-gray-900">Se Connecter</a>
                    <a href="/register" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700">Créer un Compte</a>
                </nav>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Subject Header -->
        <div class="text-center mb-12">
            <div class="inline-flex items-center justify-center w-24 h-24 rounded-full mb-6" 
                 style="background: linear-gradient(135deg, <?= $subject['color'] ?>20, <?= $subject['color'] ?>10);">
                <span class="text-5xl"><?= $subject['icon'] ?></span>
            </div>
            <h1 class="text-4xl font-bold text-gray-900 mb-4"><?= htmlspecialchars($subject['name']) ?></h1>
            <p class="text-xl text-gray-600 max-w-3xl mx-auto"><?= htmlspecialchars($subject['description']) ?></p>
        </div>

        <!-- Themes Section -->
        <div class="mb-16">
            <h2 class="text-3xl font-bold text-gray-900 mb-8 text-center">Thèmes disponibles</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <?php foreach ($themes as $theme): ?>
                <div class="bg-white rounded-xl shadow-lg p-6 hover:shadow-xl transition-shadow duration-300">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-xl font-bold text-gray-900"><?= htmlspecialchars($theme['name']) ?></h3>
                        <span class="px-3 py-1 rounded-full text-sm font-medium 
                            <?= $theme['difficulty'] === 'facile' ? 'bg-green-100 text-green-800' : 
                                ($theme['difficulty'] === 'moyen' ? 'bg-yellow-100 text-yellow-800' : 'bg-red-100 text-red-800') ?>">
                            <?= ucfirst($theme['difficulty']) ?>
                        </span>
                    </div>
                    <p class="text-gray-600 mb-4"><?= htmlspecialchars($theme['description']) ?></p>
                    <button class="w-full bg-blue-600 text-white px-4 py-3 rounded-lg hover:bg-blue-700 transition-colors duration-200 font-medium">
                        Commencer le thème
                    </button>
                </div>
                <?php endforeach; ?>
            </div>
        </div>

        <!-- Sample Questions Section -->
        <?php if (!empty($questions)): ?>
        <div class="mb-16">
            <h2 class="text-3xl font-bold text-gray-900 mb-8 text-center">Exemples de questions</h2>
            <div class="space-y-6">
                <?php foreach ($questions as $question): ?>
                <div class="bg-white rounded-xl shadow-lg p-6">
                    <div class="mb-4">
                        <span class="inline-block bg-gray-100 text-gray-700 px-3 py-1 rounded-full text-sm font-medium mb-3">
                            <?= htmlspecialchars($question['theme_name']) ?>
                        </span>
                        <h3 class="text-lg font-semibold text-gray-900"><?= htmlspecialchars($question['question']) ?></h3>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-3 mb-4">
                        <?php 
                        $options = json_decode($question['options'], true);
                        $correctAnswer = $question['correct_answer'];
                        foreach ($options as $index => $option): 
                        ?>
                        <div class="flex items-center p-3 rounded-lg border-2 
                            <?= $index === $correctAnswer ? 'border-green-200 bg-green-50' : 'border-gray-200 hover:border-gray-300' ?>">
                            <div class="w-4 h-4 rounded-full mr-3 
                                <?= $index === $correctAnswer ? 'bg-green-500' : 'bg-gray-300' ?>"></div>
                            <span class="<?= $index === $correctAnswer ? 'text-green-800 font-medium' : 'text-gray-700' ?>">
                                <?= htmlspecialchars($option) ?>
                            </span>
                            <?php if ($index === $correctAnswer): ?>
                            <span class="ml-auto text-green-600 font-medium">✓ Correct</span>
                            <?php endif; ?>
                        </div>
                        <?php endforeach; ?>
                    </div>
                    
                    <?php if ($question['explanation']): ?>
                    <div class="bg-blue-50 border-l-4 border-blue-400 p-4 rounded-r-lg">
                        <p class="text-blue-800">
                            <strong>Explication :</strong> <?= htmlspecialchars($question['explanation']) ?>
                        </p>
                    </div>
                    <?php endif; ?>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
        <?php endif; ?>

        <!-- Call to Action -->
        <div class="text-center">
            <div class="bg-gradient-to-r from-blue-600 to-purple-600 rounded-2xl p-8 text-white">
                <h2 class="text-3xl font-bold mb-4">Prêt à tester vos connaissances ?</h2>
                <p class="text-xl mb-6 opacity-90">
                    Commencez par un thème facile et progressez à votre rythme
                </p>
                <a href="/register" class="bg-white text-blue-600 px-8 py-4 rounded-lg text-lg font-bold hover:bg-gray-100 transition-colors duration-200 inline-block">
                    🚀 Commencer l'apprentissage
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

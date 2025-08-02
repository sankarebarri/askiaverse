<?php
// On inclut notre gabarit de base qui contient le header, le footer, etc.
// Note : Assurez-vous que ce chemin est correct par rapport à votre structure.
require_once __DIR__ . '/layouts/app.php';
?>

<!-- Le contenu spécifique de la page des quiz commence ici -->
<div class="container mx-auto px-4 py-8">
    <h1 class="text-3xl font-bold mb-6">Liste des Quiz Disponibles</h1>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        <?php if (empty($quizzes)): ?>
            <p class="text-gray-500">Aucun quiz disponible pour le moment.</p>
        <?php else: ?>
            <?php foreach ($quizzes as $quiz): ?>
                <div class="bg-white rounded-lg shadow-md p-6">
                    <span class="text-sm font-semibold text-indigo-600"><?= htmlspecialchars($quiz['topic_name']) ?></span>
                    <h2 class="text-xl font-bold mt-2 mb-2"><?= htmlspecialchars($quiz['title']) ?></h2>
                    <p class="text-gray-600 mb-4"><?= htmlspecialchars($quiz['description']) ?></p>
                    <a href="#" class="bg-indigo-600 text-white font-bold py-2 px-4 rounded hover:bg-indigo-700 transition duration-300">
                        Commencer le Quiz
                    </a>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</div>
<!-- Fin du contenu spécifique -->
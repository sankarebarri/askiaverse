<?php
// ===================================================================
// FICHIER : resources/views/layouts/app.php
// C'est notre gabarit (template) de page principal.
// Toutes nos autres vues hériteront de cette structure.
// ===================================================================

// On inclut notre fichier d'aide pour pouvoir utiliser la fonction vite_css_tag().
// Le chemin est relatif à notre 'public/index.php' qui inclura ce fichier.
require_once __DIR__ . '/../../../src/Shared/Helpers.php';

// Get current page for navigation highlighting
$current_page = $current_page ?? 'index';
?>
<!DOCTYPE html>
<html lang="fr" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $page_title ?? 'Askiaverse' ?></title>

    <!-- On utilise notre fonction d'aide pour générer la balise CSS. -->
    <!-- Elle trouvera automatiquement le bon fichier CSS compilé avec le hash. -->
    <?= vite_css_tag('resources/css/app.css') ?>

</head>
<body class="h-full font-sans antialiased">

    <!-- Header with HUD (only for non-index and non-auth pages) -->
    <?php if (!in_array($current_page, ['index', 'login', 'register'])): ?>
    <header class="main-header">
        <div class="header-content">
            <a href="?page=index" class="logo">Askiaverse</a>
            <div class="hud">
                <div class="hud-item" title="Niveau">LVL <span id="level-stat">5</span></div>
                <div class="hud-item xp-bar-container" title="Expérience">
                    <div id="xp-bar" class="xp-bar" style="width: 75%"></div>
                </div>
                <div class="hud-item" title="Askia Orbs">🪙 <span id="orbs-stat">500</span></div>
                <div class="hud-item" title="Jetons Focus">🧘 <span id="focus-tokens-stat">3</span></div>
            </div>
            <div class="user-menu">
                <button id="user-menu-btn" class="user-menu-btn">
                    <div class="avatar-placeholder"></div>
                    <span id="username-display">hamza</span>
                    <span class="arrow-down">▼</span>
                </button>
                <div id="user-dropdown" class="user-dropdown hidden">
                    <a href="#" class="dropdown-item disabled">Profil (bientôt)</a>
                    <a href="#" class="dropdown-item disabled">Paramètres (bientôt)</a>
                    <a href="#" id="how-to-play-btn" class="dropdown-item">Comment Jouer</a>
                    <hr>
                    <a href="?page=logout" id="logout-button" class="dropdown-item">Se Déconnecter</a>
                </div>
            </div>
        </div>
    </header>
    <?php endif; ?>

    <!-- Navigation (only for non-index and non-auth pages) -->
    <?php if (!in_array($current_page, ['index', 'login', 'register'])): ?>
    <nav class="main-nav">
        <a href="?page=dashboard" class="nav-link <?= $current_page === 'dashboard' ? 'active' : '' ?>">📚 Apprendre</a>
        <a href="?page=competition" class="nav-link <?= $current_page === 'competition' ? 'active' : '' ?>">🏆 Compétition</a>
        <a href="?page=community" class="nav-link <?= $current_page === 'community' ? 'active' : '' ?>">👥 Communauté</a>
    </nav>
    <?php endif; ?>

    <!-- Main Content -->
    <main class="container py-8">
        <?php
        // Include the appropriate page content
        if ($view_file && file_exists($view_file)) {
            include $view_file;
        } else {
            echo '<div class="text-center py-12">';
            echo '<h2 class="text-2xl font-bold text-gray-700">Page non trouvée</h2>';
            echo '<p class="text-gray-500 mt-2">Page demandée: ' . htmlspecialchars($current_page) . '</p>';
            echo '</div>';
        }
        ?>
    </main>

    <script>
        // User menu functionality (only if header exists)
        const userMenuBtn = document.getElementById('user-menu-btn');
        if (userMenuBtn) {
            userMenuBtn.addEventListener('click', function() {
                this.classList.toggle('open');
                document.getElementById('user-dropdown').classList.toggle('hidden');
            });
            
            // Close dropdown when clicking outside
            document.addEventListener('click', function(e) {
                if (!e.target.closest('.user-menu')) {
                    document.getElementById('user-menu-btn').classList.remove('open');
                    document.getElementById('user-dropdown').classList.add('hidden');
                }
            });

            // Update HUD stats (placeholder - will be connected to backend)
            function updateHUD() {
                document.getElementById('level-stat').textContent = '5';
                document.getElementById('orbs-stat').textContent = '500';
                document.getElementById('focus-tokens-stat').textContent = '3';
                document.getElementById('xp-bar').style.width = '75%';
                document.getElementById('username-display').textContent = 'hamza';
            }

            // Initialize
            updateHUD();
        }
    </script>
</body>
</html>

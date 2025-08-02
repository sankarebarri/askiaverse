<?php
require_once __DIR__ . '/../../../src/Shared/Helpers.php';

// Get current page for navigation highlighting
$current_page = $_GET['page'] ?? 'dashboard';
?>
<!DOCTYPE html>
<html lang="fr" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $page_title ?? 'Askiaverse' ?></title>
    <?= vite_css_tag('resources/css/app.css') ?>
</head>
<body>
    <!-- Header with HUD -->
    <header class="main-header">
        <div class="header-content">
            <a href="/" class="logo">Askiaverse</a>
            <div class="hud">
                <div class="hud-item" title="Niveau">LVL <span id="level-stat">5</span></div>
                <div class="hud-item xp-bar-container" title="Expérience">
                    <div id="xp-bar" class="xp-bar" style="width: 75%"></div>
                </div>
                <div class="hud-item" title="Askia Orbs">🪙 <span id="orbs-stat">150</span></div>
                <div class="hud-item" title="Jetons Focus">🧘 <span id="focus-tokens-stat">3</span></div>
            </div>
            <div class="user-menu">
                <button id="user-menu-btn" class="user-menu-btn">
                    <div class="avatar-placeholder"></div>
                    <span id="username-display">TestUser</span>
                    <span class="arrow-down">▼</span>
                </button>
                <div id="user-dropdown" class="user-dropdown hidden">
                    <a href="#" class="dropdown-item disabled">Profil (bientôt)</a>
                    <a href="#" class="dropdown-item disabled">Paramètres (bientôt)</a>
                    <a href="#" id="how-to-play-btn" class="dropdown-item">Comment Jouer</a>
                    <hr>
                    <a href="/logout" id="logout-button" class="dropdown-item">Se Déconnecter</a>
                </div>
            </div>
        </div>
    </header>

    <!-- Navigation -->
    <nav class="main-nav">
        <a href="/dashboard" class="nav-link <?= $current_page === 'dashboard' ? 'active' : '' ?>">📚 Apprendre</a>
        <a href="/competition" class="nav-link <?= $current_page === 'competition' ? 'active' : '' ?>">🏆 Compétition</a>
        <a href="/community" class="nav-link <?= $current_page === 'community' ? 'active' : '' ?>">👥 Communauté</a>
    </nav>

    <!-- Main Content -->
    <main class="container py-8">
        <?= $content ?? '' ?>
    </main>

    <script>
        // User menu functionality
        document.getElementById('user-menu-btn').addEventListener('click', function() {
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
            // This will be replaced with real data from the backend
            document.getElementById('level-stat').textContent = '5';
            document.getElementById('orbs-stat').textContent = '150';
            document.getElementById('focus-tokens-stat').textContent = '3';
            document.getElementById('xp-bar').style.width = '75%';
        }

        // Initialize HUD
        updateHUD();
    </script>
</body>
</html> 
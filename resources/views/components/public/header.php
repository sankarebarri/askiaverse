<!-- Header with HUD -->
<header class="main-header">
    <div class="header-content">
        <a href="/" class="logo">Askiaverse</a>
        <div class="hud">
            <div class="hud-item" title="Niveau">NIV <span id="level-stat"><?= $user_stats['level'] ?? 1 ?></span></div>
            <div class="hud-item xp-bar-container" title="Expérience">
                <div id="xp-bar" class="xp-bar" style="width: <?= $user_stats['xp_percentage'] ?? 0 ?>%"></div>
            </div>
            <div class="hud-item" title="Askia Orbs">🪙 <span id="orbs-stat"><?= $user_stats['orbs'] ?? 0 ?></span></div>
            <div class="hud-item" title="Jetons Focus">🧘 <span id="focus-tokens-stat"><?= $user_stats['focus_tokens'] ?? 0 ?></span></div>
        </div>
        <div class="user-menu">
            <button id="user-menu-btn" class="user-menu-btn">
                <div class="avatar-placeholder"></div>
                <span id="username-display"><?= htmlspecialchars($_SESSION['username'] ?? 'Invité') ?></span>
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
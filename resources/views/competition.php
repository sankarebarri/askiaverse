<?php
$page_title = 'Compétition - Askiaverse';
?>
<!DOCTYPE html>
<html lang="fr" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $page_title ?></title>
    <link rel="stylesheet" href="/assets/app.css">
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
        <a href="/dashboard" class="nav-link">📚 Apprendre</a>
        <a href="/competition" class="nav-link active">🏆 Compétition</a>
        <a href="/community" class="nav-link">👥 Communauté</a>
    </nav>

    <!-- Main Content -->
    <main class="container py-8">
        <!-- Competition Content -->
        <div class="competition-container">
    <!-- Main Content Area -->
    <div class="competition-layout">
        <!-- Left Panel - National Ranking -->
        <div class="ranking-panel">
            <h2 class="panel-title">Classement National - Semaine 1</h2>
            
            <!-- Tabs -->
            <div class="ranking-tabs">
                <button class="tab-btn active" data-tab="national">National</button>
                <button class="tab-btn" data-tab="regional">Régional</button>
            </div>
            
            <!-- Ranking Table -->
            <div class="ranking-table">
                <table>
                    <thead>
                        <tr>
                            <th>Rang</th>
                            <th>École</th>
                            <th>Ville</th>
                            <th>Score Moyen</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td class="rank">#1</td>
                            <td class="school">École Liberté</td>
                            <td class="city">Sikasso</td>
                            <td class="score">8.75</td>
                        </tr>
                        <tr class="highlighted">
                            <td class="rank">#2</td>
                            <td class="school">École Pilote</td>
                            <td class="city">Gao</td>
                            <td class="score">8.50</td>
                        </tr>
                        <tr>
                            <td class="rank">#3</td>
                            <td class="school">Le Flamboyant</td>
                            <td class="city">Bamako</td>
                            <td class="score">8.33</td>
                        </tr>
                        <tr>
                            <td class="rank">#4</td>
                            <td class="school">Lycée Askia</td>
                            <td class="city">Bamako</td>
                            <td class="score">8.00</td>
                        </tr>
                        <tr>
                            <td class="rank">#5</td>
                            <td class="school">Gao International School</td>
                            <td class="city">Gao</td>
                            <td class="score">7.92</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
        
        <!-- Right Sidebar -->
        <div class="competition-sidebar">
            <!-- Challenge Card -->
            <div class="challenge-card">
                <h3 class="card-title">Défi de la Semaine 1</h3>
                <p class="challenge-description">Un quiz mixte pour tester toutes vos connaissances!</p>
                <div class="countdown">
                    <span class="countdown-label">La compétition se termine dans :</span>
                    <div class="countdown-timer" id="countdown-timer">2j 23h 59m 42s</div>
                </div>
                <button class="play-button">Jouer pour mon École!</button>
            </div>
            
            <!-- School Stats Card -->
            <div class="school-stats-card">
                <h3 class="card-title">Statistiques de École Pilote</h3>
                <div class="stats-grid">
                    <div class="stat-item">
                        <span class="stat-label">Classement National</span>
                        <span class="stat-value">#2</span>
                    </div>
                    <div class="stat-item">
                        <span class="stat-label">Score Moyen</span>
                        <span class="stat-value">8.50</span>
                    </div>
                    <div class="stat-item">
                        <span class="stat-label">Participants</span>
                        <span class="stat-value">10</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Hall of Fame Section -->
    <div class="hall-of-fame">
        <h2 class="hof-title">Tableau d'Honneur</h2>
        <div class="hof-cards">
            <!-- Legends of the Week -->
            <div class="hof-card">
                <h3 class="hof-card-title">Légendes de la Semaine</h3>
                <div class="hof-list">
                    <div class="hof-item">
                        <span class="hof-rank">#1</span>
                        <span class="hof-name">Aïcha</span>
                        <span class="hof-school">(Le Flamboyant)</span>
                        <span class="hof-score">10/10</span>
                    </div>
                    <div class="hof-item">
                        <span class="hof-rank">#2</span>
                        <span class="hof-name">Moussa</span>
                        <span class="hof-school">(Lycée Askia)</span>
                        <span class="hof-score">9/10</span>
                    </div>
                    <div class="hof-item">
                        <span class="hof-rank">#3</span>
                        <span class="hof-name">hamza</span>
                        <span class="hof-school">(École Pilote)</span>
                        <span class="hof-score">9/10</span>
                    </div>
                </div>
            </div>
            
            <!-- School Champions -->
            <div class="hof-card">
                <h3 class="hof-card-title">Champions de l'École</h3>
                <div class="hof-list">
                    <div class="hof-item">
                        <span class="hof-rank">#1</span>
                        <span class="hof-name">hamza</span>
                        <span class="hof-score">9/10</span>
                    </div>
                    <div class="hof-item">
                        <span class="hof-rank">#2</span>
                        <span class="hof-name">Mariam</span>
                        <span class="hof-score">8/10</span>
                    </div>
                </div>
            </div>
            
            <!-- Rising Stars -->
            <div class="hof-card">
                <h3 class="hof-card-title">Étoiles Montantes</h3>
                <div class="rising-star">
                    <div class="star-icon">🚀</div>
                    <div class="star-content">
                        <div class="star-school">École Liberté</div>
                        <div class="star-improvement">+1.5 points d'amélioration</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    // Tab functionality
    document.querySelectorAll('.tab-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            // Remove active class from all tabs
            document.querySelectorAll('.tab-btn').forEach(b => b.classList.remove('active'));
            // Add active class to clicked tab
            this.classList.add('active');
            
            // Here you would typically load different data based on the tab
            console.log('Switched to tab:', this.dataset.tab);
        });
    });

    // Countdown timer
    function updateCountdown() {
        // This is a placeholder - in a real app, you'd calculate the actual time remaining
        const timer = document.getElementById('countdown-timer');
        if (timer) {
            // For demo purposes, just show a static time
            timer.textContent = '2j 23h 59m 42s';
        }
    }

    // Update countdown every second
    setInterval(updateCountdown, 1000);
    updateCountdown();

    // Play button functionality
    document.querySelector('.play-button').addEventListener('click', function() {
        console.log('Starting competition quiz...');
        // Redirect to quiz with competition mode
        window.location.href = '/quiz?subject=mathematics&theme=arithmetic&mode=competition';
    });

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
        </div>
    </main>
</body>
</html> 
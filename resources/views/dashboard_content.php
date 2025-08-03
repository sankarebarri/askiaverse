<!-- Dashboard Content -->
<div class="dashboard-container">
    <!-- Welcome Section -->
    <div class="welcome-section">
        <h1 class="welcome-title">👋 Bonjour, <?= htmlspecialchars($user['username']) ?>!</h1>
        <p class="welcome-subtitle">Prêt à apprendre et à progresser ? Choisissez votre défi du jour !</p>
    </div>

    <!-- User Stats Section -->
    <div class="stats-section">
        <h2 class="section-title">Mes Statistiques</h2>
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-icon">📊</div>
                <div class="stat-content">
                    <div class="stat-value"><?= $stats['total_quizzes'] ?></div>
                    <div class="stat-label">Quiz Complétés</div>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-icon">🎯</div>
                <div class="stat-content">
                    <div class="stat-value"><?= $stats['avg_score'] ?>%</div>
                    <div class="stat-label">Score Moyen</div>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-icon">🏆</div>
                <div class="stat-content">
                    <div class="stat-value"><?= $stats['best_score'] ?>%</div>
                    <div class="stat-label">Meilleur Score</div>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-icon">📈</div>
                <div class="stat-content">
                    <div class="stat-value"><?= $stats['accuracy'] ?>%</div>
                    <div class="stat-label">Précision</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Game Cards Section -->
    <div class="games-section">
        <h2 class="section-title">🎮 Défis Disponibles</h2>
        <div class="game-cards">
            <div class="game-card" onclick="startQuiz('mathematics', 'arithmetic', 'quick-quiz')">
                <div class="game-icon">🔢</div>
                <div class="game-content">
                    <h3 class="game-title">Mathématiques</h3>
                    <p class="game-subtitle">Arithmétique</p>
                    <div class="game-difficulty">Facile</div>
                </div>
                <div class="game-arrow">→</div>
            </div>

            <div class="game-card" onclick="startQuiz('mathematics', 'geometry', 'quick-quiz')">
                <div class="game-icon">📐</div>
                <div class="game-content">
                    <h3 class="game-title">Mathématiques</h3>
                    <p class="game-subtitle">Géométrie</p>
                    <div class="game-difficulty">Moyen</div>
                </div>
                <div class="game-arrow">→</div>
            </div>

            <div class="game-card" onclick="startQuiz('science', 'physics', 'quick-quiz')">
                <div class="game-icon">⚡</div>
                <div class="game-content">
                    <h3 class="game-title">Sciences</h3>
                    <p class="game-subtitle">Physique</p>
                    <div class="game-difficulty">Difficile</div>
                </div>
                <div class="game-arrow">→</div>
            </div>

            <div class="game-card" onclick="startQuiz('history', 'mali', 'quick-quiz')">
                <div class="game-icon">🏛️</div>
                <div class="game-content">
                    <h3 class="game-title">Histoire</h3>
                    <p class="game-subtitle">Histoire du Mali</p>
                    <div class="game-difficulty">Moyen</div>
                </div>
                <div class="game-arrow">→</div>
            </div>
        </div>
    </div>

    <!-- Recent Activity Section -->
    <div class="activity-section">
        <h2 class="section-title">📈 Activité Récente</h2>
        <div class="activity-list">
            <?php if (empty($stats['recent_activity'])): ?>
                <div class="empty-activity">
                    <p>Aucune activité récente. Commencez par jouer à un quiz !</p>
                </div>
            <?php else: ?>
                <?php foreach ($stats['recent_activity'] as $activity): ?>
                    <div class="activity-item">
                        <div class="activity-icon">🎯</div>
                        <div class="activity-content">
                            <div class="activity-title">Quiz complété</div>
                            <div class="activity-details">
                                Score: <?= $activity['score'] ?>% • 
                                <?= date('d/m/Y H:i', strtotime($activity['started_at'])) ?>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>
</div>

<script>
    function startQuiz(subject, theme, mode) {
        console.log(`Starting quiz: ${subject} - ${theme} - ${mode}`);
        window.location.href = `/quiz?subject=${subject}&theme=${theme}&mode=${mode}`;
    }
</script> 
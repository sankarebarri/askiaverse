<div class="welcome-section admin-card">
    <h2>👋 Bonjour, <?= htmlspecialchars($_SESSION['admin_username'] ?? 'Admin') ?>!</h2>
    <p>Bienvenue dans votre tableau de bord d'administration. Gérez votre contenu éducatif en toute simplicité.</p>
</div>

<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-icon">📚</div>
        <div class="stat-content">
            <div class="stat-value">2</div>
            <div class="stat-label">Matières</div>
        </div>
    </div>
    
    <div class="stat-card">
        <div class="stat-icon">📖</div>
        <div class="stat-content">
            <div class="stat-value">2</div>
            <div class="stat-label">Thèmes</div>
        </div>
    </div>
    
    <div class="stat-card">
        <div class="stat-icon">🎯</div>
        <div class="stat-content">
            <div class="stat-value">1</div>
            <div class="stat-label">Quiz</div>
        </div>
    </div>
    
    <div class="stat-card">
        <div class="stat-icon">❓</div>
        <div class="stat-content">
            <div class="stat-value">2</div>
            <div class="stat-label">Questions</div>
        </div>
    </div>
</div>

<div class="admin-card">
    <h2>Actions Rapides</h2>
    <div class="action-buttons">
        <a href="/admin/questions/add" class="btn-primary">➕ Ajouter une Question</a>
        <a href="/admin/subjects/add" class="btn-secondary">📚 Ajouter une Matière</a>
        <a href="/admin/topics/add" class="btn-secondary">📖 Ajouter un Thème</a>
        <a href="/admin/quiz/add" class="btn-secondary">🎯 Ajouter un Quiz</a>
    </div>
</div>

<div class="admin-card">
    <h2>Activité Récente</h2>
    <div class="activity-list">
        <div class="activity-item">
            <span class="activity-icon">✅</span>
            <span class="activity-text">Question "Que font 2 + 2 ?" a été créée</span>
            <span class="activity-time">Il y a 2 heures</span>
        </div>
        <div class="activity-item">
            <span class="activity-icon">✅</span>
            <span class="activity-text">Question "Que font 5 + 3 ?" a été créée</span>
            <span class="activity-time">Il y a 2 heures</span>
        </div>
    </div>
</div> 
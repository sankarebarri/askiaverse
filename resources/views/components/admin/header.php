<header class="admin-header">
    <div class="header-content">
        <div class="header-left">
            <h1 class="admin-title">🚀 Administration Askiaverse</h1>
            <nav class="admin-nav">
                <a href="/admin" class="nav-link <?= $current_page === 'dashboard' ? 'active' : '' ?>">Tableau de bord</a>
                <a href="/admin/questions" class="nav-link <?= $current_page === 'questions' ? 'active' : '' ?>">Questions</a>
                <a href="/admin/subjects" class="nav-link <?= $current_page === 'subjects' ? 'active' : '' ?>">Matières</a>
                <a href="/admin/users" class="nav-link <?= $current_page === 'users' ? 'active' : '' ?>">Utilisateurs</a>
            </nav>
        </div>
        <div class="header-right">
            <div class="user-info">
                <span class="user-avatar">👤</span>
                <div class="user-details">
                    <span class="user-name"><?= htmlspecialchars($_SESSION['admin_username'] ?? 'Admin') ?></span>
                    <span class="user-role"><?= ucfirst($_SESSION['admin_role'] ?? 'admin') ?></span>
                </div>
                <a href="/admin/logout" class="logout-btn" title="Se déconnecter">
                    <span class="logout-icon">🚪</span>
                </a>
            </div>
        </div>
    </div>
</header> 
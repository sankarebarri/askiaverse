<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $page_title ?></title>
    <link rel="stylesheet" href="/assets/app.css">
</head>
<body>
    <div class="admin-container">
        <header class="admin-header">
            <div class="header-content">
                <div class="header-left">
                    <h1 class="admin-title">🚀 Administration Askiaverse</h1>
                    <nav class="admin-nav">
                        <a href="/admin" class="nav-link active">Tableau de bord</a>
                        <a href="/admin/questions" class="nav-link">Questions</a>
                        <a href="/admin/subjects" class="nav-link">Matières</a>
                        <a href="/admin/users" class="nav-link">Utilisateurs</a>
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

        <main class="admin-main">
            <div class="welcome-section">
                <h2>👋 Bonjour, <?= htmlspecialchars($_SESSION['admin_username'] ?? 'Admin') ?>!</h2>
                <p>Bienvenue dans votre tableau de bord d'administration. Gérez votre contenu éducatif en toute simplicité.</p>
            </div>

            <div class="dashboard-stats">
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

            <div class="quick-actions">
                <h2>Actions Rapides</h2>
                <div class="action-buttons">
                    <a href="/admin/questions/add" class="btn-primary">➕ Ajouter une Question</a>
                    <a href="/admin/subjects/add" class="btn-secondary">📚 Ajouter une Matière</a>
                    <a href="/admin/topics/add" class="btn-secondary">📖 Ajouter un Thème</a>
                    <a href="/admin/quiz/add" class="btn-secondary">🎯 Ajouter un Quiz</a>
                </div>
            </div>

            <div class="recent-activity">
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
        </main>
    </div>

    <style>
        .admin-container {
            min-height: 100vh;
            background: linear-gradient(135deg, #0f1419, #1a2332, #0f1419);
        }

        .admin-header {
            background: #253549e6;
            backdrop-filter: blur(10px);
            border-bottom: 1px solid rgba(255,255,255,.1);
            padding: 1rem 2rem;
        }

        .header-content {
            max-width: 1400px;
            margin: 0 auto;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .header-left {
            display: flex;
            align-items: center;
            gap: 2rem;
        }

        .header-right {
            display: flex;
            align-items: center;
        }

        .admin-title {
            font-size: 1.5rem;
            font-weight: 700;
            color: #fff;
            font-family: Poppins, sans-serif;
            margin: 0;
        }

        .admin-nav {
            display: flex;
            gap: 1rem;
        }

        .nav-link {
            padding: 0.5rem 1rem;
            border-radius: 8px;
            color: #fff;
            text-decoration: none;
            font-weight: 600;
            transition: all 0.3s;
        }

        .nav-link:hover {
            background: rgba(255,255,255,.1);
        }

        .nav-link.active {
            background: #4a90e2;
        }

        .user-info {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            background: rgba(255,255,255,.1);
            border-radius: 12px;
            padding: 0.75rem 1rem;
            border: 1px solid rgba(255,255,255,.2);
        }

        .user-avatar {
            font-size: 1.5rem;
            width: 2.5rem;
            height: 2.5rem;
            background: #4a90e2;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .user-details {
            display: flex;
            flex-direction: column;
            gap: 0.25rem;
        }

        .user-name {
            color: #fff;
            font-weight: 700;
            font-size: 0.9rem;
        }

        .user-role {
            color: #ffffffb3;
            font-size: 0.8rem;
            font-weight: 500;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .logout-btn {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 2.5rem;
            height: 2.5rem;
            background: rgba(248,113,113,.2);
            border: 1px solid rgba(248,113,113,.3);
            border-radius: 8px;
            color: #f87171;
            text-decoration: none;
            transition: all 0.3s;
        }

        .logout-btn:hover {
            background: rgba(248,113,113,.3);
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(248,113,113,.2);
        }

        .logout-icon {
            font-size: 1.2rem;
        }

        .admin-main {
            max-width: 1400px;
            margin: 0 auto;
            padding: 2rem;
        }

        .welcome-section {
            background: #253549e6;
            border: 1px solid rgba(255,255,255,.1);
            border-radius: 16px;
            padding: 2rem;
            margin-bottom: 2rem;
            text-align: center;
        }

        .welcome-section h2 {
            font-size: 2rem;
            font-weight: 700;
            color: #fff;
            font-family: Poppins, sans-serif;
            margin: 0 0 1rem 0;
        }

        .welcome-section p {
            color: #ffffffb3;
            font-size: 1.1rem;
            margin: 0;
            line-height: 1.6;
        }

        .dashboard-stats {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1.5rem;
            margin-bottom: 3rem;
        }

        .stat-card {
            background: #253549e6;
            border: 1px solid rgba(255,255,255,.1);
            border-radius: 16px;
            padding: 1.5rem;
            text-align: center;
            transition: all 0.3s;
        }

        .stat-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 8px 24px rgba(74,144,226,.2);
        }

        .stat-icon {
            font-size: 2.5rem;
            margin-bottom: 1rem;
        }

        .stat-value {
            font-size: 2rem;
            font-weight: 700;
            color: #fff;
            font-family: Poppins, sans-serif;
        }

        .stat-label {
            color: #ffffffb3;
            font-size: 0.9rem;
            font-weight: 600;
        }

        .quick-actions, .recent-activity {
            background: #253549e6;
            border: 1px solid rgba(255,255,255,.1);
            border-radius: 16px;
            padding: 2rem;
            margin-bottom: 2rem;
        }

        .quick-actions h2, .recent-activity h2 {
            font-size: 1.5rem;
            font-weight: 700;
            color: #fff;
            margin-bottom: 1.5rem;
            font-family: Poppins, sans-serif;
        }

        .action-buttons {
            display: flex;
            gap: 1rem;
            flex-wrap: wrap;
        }

        .btn-primary, .btn-secondary {
            padding: 0.75rem 1.5rem;
            border-radius: 8px;
            text-decoration: none;
            font-weight: 700;
            transition: all 0.3s;
        }

        .btn-primary {
            background: linear-gradient(135deg, #4a90e2, #2a4d69);
            color: #fff;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(74,144,226,.4);
        }

        .btn-secondary {
            background: transparent;
            border: 2px solid #4a90e2;
            color: #4a90e2;
        }

        .btn-secondary:hover {
            background: #4a90e21a;
            transform: translateY(-2px);
        }

        .activity-list {
            display: flex;
            flex-direction: column;
            gap: 1rem;
        }

        .activity-item {
            display: flex;
            align-items: center;
            gap: 1rem;
            padding: 1rem;
            background: rgba(255,255,255,.05);
            border-radius: 8px;
        }

        .activity-icon {
            font-size: 1.2rem;
        }

        .activity-text {
            flex: 1;
            color: #fff;
        }

        .activity-time {
            color: #ffffffb3;
            font-size: 0.9rem;
        }
    </style>
</body>
</html> 
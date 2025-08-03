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
                        <a href="/admin" class="nav-link">Tableau de bord</a>
                        <a href="/admin/questions" class="nav-link active">Questions</a>
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
            <div class="page-header">
                <h1>❓ Gestion des Questions</h1>
                <a href="/admin/questions/add" class="btn-primary">➕ Ajouter une Question</a>
            </div>

            <div class="questions-container">
                <?php if (empty($questions)): ?>
                    <div class="empty-state">
                        <div class="empty-icon">❓</div>
                        <h3>Aucune question trouvée</h3>
                        <p>Commencez par ajouter votre première question pour créer du contenu éducatif.</p>
                        <a href="/admin/questions/add" class="btn-primary">➕ Ajouter une Question</a>
                    </div>
                <?php else: ?>
                    <div class="questions-list">
                        <?php foreach ($questions as $question): ?>
                            <div class="question-card">
                                <div class="question-header">
                                    <div class="question-info">
                                        <h3 class="question-text"><?= htmlspecialchars($question['text']) ?></h3>
                                        <div class="question-meta">
                                            <span class="meta-item">
                                                <span class="meta-label">Matière:</span>
                                                <span class="meta-value"><?= htmlspecialchars($question['subject_name']) ?></span>
                                            </span>
                                            <span class="meta-item">
                                                <span class="meta-label">Thème:</span>
                                                <span class="meta-value"><?= htmlspecialchars($question['topic_name']) ?></span>
                                            </span>
                                            <span class="meta-item">
                                                <span class="meta-label">Difficulté:</span>
                                                <span class="meta-value difficulty-<?= $question['difficulty_level'] ?>">
                                                    <?= ucfirst($question['difficulty_level']) ?>
                                                </span>
                                            </span>
                                        </div>
                                    </div>
                                    <div class="question-actions">
                                        <span class="option-count"><?= $question['option_count'] ?> options</span>
                                        <div class="action-buttons">
                                            <button class="btn-secondary btn-sm">✏️ Modifier</button>
                                            <button class="btn-secondary btn-sm">👁️ Aperçu</button>
                                            <button class="btn-secondary btn-sm btn-danger">🗑️ Supprimer</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
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

        .page-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 2rem;
        }

        .page-header h1 {
            font-size: 2rem;
            font-weight: 700;
            color: #fff;
            font-family: Poppins, sans-serif;
            margin: 0;
        }

        .questions-container {
            background: #253549e6;
            border: 1px solid rgba(255,255,255,.1);
            border-radius: 16px;
            padding: 2rem;
        }

        .empty-state {
            text-align: center;
            padding: 4rem 2rem;
            color: #fff;
        }

        .empty-icon {
            font-size: 4rem;
            margin-bottom: 1rem;
        }

        .empty-state h3 {
            font-size: 1.5rem;
            font-weight: 700;
            margin-bottom: 1rem;
            font-family: Poppins, sans-serif;
        }

        .empty-state p {
            color: #ffffffb3;
            margin-bottom: 2rem;
            font-size: 1.1rem;
        }

        .questions-list {
            display: flex;
            flex-direction: column;
            gap: 1rem;
        }

        .question-card {
            background: rgba(255,255,255,.05);
            border: 1px solid rgba(255,255,255,.1);
            border-radius: 12px;
            padding: 1.5rem;
            transition: all 0.3s;
        }

        .question-card:hover {
            background: rgba(255,255,255,.08);
            transform: translateY(-2px);
        }

        .question-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            gap: 1rem;
        }

        .question-info {
            flex: 1;
        }

        .question-text {
            font-size: 1.1rem;
            font-weight: 600;
            color: #fff;
            margin: 0 0 1rem 0;
            line-height: 1.4;
        }

        .question-meta {
            display: flex;
            gap: 1.5rem;
            flex-wrap: wrap;
        }

        .meta-item {
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .meta-label {
            color: #ffffffb3;
            font-size: 0.9rem;
            font-weight: 600;
        }

        .meta-value {
            color: #fff;
            font-size: 0.9rem;
            font-weight: 500;
        }

        .difficulty-easy {
            color: #4ade80;
        }

        .difficulty-medium {
            color: #fbbf24;
        }

        .difficulty-hard {
            color: #f87171;
        }

        .question-actions {
            display: flex;
            flex-direction: column;
            align-items: flex-end;
            gap: 0.75rem;
        }

        .option-count {
            color: #ffffffb3;
            font-size: 0.9rem;
            font-weight: 600;
        }

        .action-buttons {
            display: flex;
            gap: 0.5rem;
        }

        .btn-primary, .btn-secondary {
            padding: 0.75rem 1.5rem;
            border-radius: 8px;
            text-decoration: none;
            font-weight: 700;
            transition: all 0.3s;
            border: none;
            cursor: pointer;
            font-size: 1rem;
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

        .btn-sm {
            padding: 0.5rem 1rem;
            font-size: 0.9rem;
        }

        .btn-danger {
            border-color: #f87171;
            color: #f87171;
        }

        .btn-danger:hover {
            background: rgba(248,113,113,.1);
        }
    </style>
</body>
</html> 
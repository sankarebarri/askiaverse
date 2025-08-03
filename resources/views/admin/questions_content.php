<?php
$page_header = [
    'title' => '❓ Gestion des Questions',
    'actions' => [
        [
            'url' => '/admin/questions/add',
            'text' => '➕ Ajouter une Question',
            'type' => 'primary'
        ]
    ]
];
?>

<div class="admin-card">
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
                                <button class="btn-secondary btn-sm btn-danger" data-confirm="Êtes-vous sûr de vouloir supprimer cette question ?">🗑️ Supprimer</button>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div> 
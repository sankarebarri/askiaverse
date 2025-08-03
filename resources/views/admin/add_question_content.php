<?php
$page_header = [
    'title' => '➕ Ajouter une Nouvelle Question',
    'actions' => [
        [
            'url' => '/admin/questions',
            'text' => '← Retour aux Questions',
            'type' => 'secondary'
        ]
    ]
];
?>

<div class="admin-card">
    <form id="questionForm" class="question-form">
        <div class="form-section">
            <h3>📚 Détails de la Question</h3>
            
            <div class="form-group">
                <label for="topic">Matière & Thème *</label>
                <select id="topic" name="topic_id" required>
                    <option value="">Sélectionnez un thème...</option>
                    <?php foreach ($topics as $topic): ?>
                        <option value="<?= $topic['topic_id'] ?>">
                            <?= htmlspecialchars($topic['subject_name']) ?> → <?= htmlspecialchars($topic['topic_name']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="form-group">
                <label for="questionText">Texte de la Question *</label>
                <textarea id="questionText" name="question_text" rows="3" required 
                          placeholder="Entrez votre question ici..."></textarea>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label for="difficulty">Niveau de Difficulté</label>
                    <select id="difficulty" name="difficulty_level">
                        <option value="easy">Facile</option>
                        <option value="medium" selected>Moyen</option>
                        <option value="hard">Difficile</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="questionType">Type de Question</label>
                    <select id="questionType" name="question_type">
                        <option value="regular" selected>Régulière</option>
                        <option value="jackpot">Jackpot</option>
                    </select>
                </div>
            </div>
        </div>

        <div class="form-section">
            <h3>🔘 Options de Réponse</h3>
            <p class="section-help">Ajoutez 2-4 options de réponse. Sélectionnez la bonne réponse.</p>
            
            <div id="optionsContainer">
                <div class="option-group">
                    <div class="option-header">
                        <span class="option-number">1</span>
                        <label class="correct-radio">
                            <input type="radio" name="correct_answer" value="0" required>
                            <span class="radio-label">Bonne Réponse</span>
                        </label>
                    </div>
                    <textarea name="options[0][text]" placeholder="Entrez l'option 1..." required></textarea>
                </div>

                <div class="option-group">
                    <div class="option-header">
                        <span class="option-number">2</span>
                        <label class="correct-radio">
                            <input type="radio" name="correct_answer" value="1">
                            <span class="radio-label">Bonne Réponse</span>
                        </label>
                    </div>
                    <textarea name="options[1][text]" placeholder="Entrez l'option 2..." required></textarea>
                </div>

                <div class="option-group">
                    <div class="option-header">
                        <span class="option-number">3</span>
                        <label class="correct-radio">
                            <input type="radio" name="correct_answer" value="2">
                            <span class="radio-label">Bonne Réponse</span>
                        </label>
                    </div>
                    <textarea name="options[2][text]" placeholder="Entrez l'option 3..." required></textarea>
                </div>

                <div class="option-group">
                    <div class="option-header">
                        <span class="option-number">4</span>
                        <label class="correct-radio">
                            <input type="radio" name="correct_answer" value="3">
                            <span class="radio-label">Bonne Réponse</span>
                        </label>
                    </div>
                    <textarea name="options[3][text]" placeholder="Entrez l'option 4..." required></textarea>
                </div>
            </div>

            <div class="options-controls">
                <button type="button" id="addOption" class="btn-secondary">➕ Ajouter une Option</button>
                <button type="button" id="removeOption" class="btn-secondary">➖ Supprimer une Option</button>
            </div>
        </div>

        <div class="form-actions">
            <button type="submit" class="btn-primary">💾 Enregistrer la Question</button>
            <button type="button" id="previewBtn" class="btn-secondary">👁️ Aperçu</button>
            <a href="/admin/questions" class="btn-secondary">❌ Annuler</a>
        </div>
    </form>
</div> 
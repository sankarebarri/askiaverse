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
                <h1>➕ Ajouter une Nouvelle Question</h1>
                <a href="/admin/questions" class="btn-secondary">← Retour aux Questions</a>
            </div>

            <div class="question-form-container">
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
            max-width: 800px;
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

        .question-form-container {
            background: #253549e6;
            border: 1px solid rgba(255,255,255,.1);
            border-radius: 16px;
            padding: 2rem;
        }

        .form-section {
            margin-bottom: 2rem;
            padding-bottom: 2rem;
            border-bottom: 1px solid rgba(255,255,255,.1);
        }

        .form-section:last-child {
            border-bottom: none;
            margin-bottom: 0;
        }

        .form-section h3 {
            font-size: 1.3rem;
            font-weight: 700;
            color: #fff;
            margin-bottom: 1rem;
            font-family: Poppins, sans-serif;
        }

        .section-help {
            color: #ffffffb3;
            font-size: 0.9rem;
            margin-bottom: 1.5rem;
        }

        .form-group {
            margin-bottom: 1.5rem;
        }

        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 1rem;
        }

        .form-group label {
            display: block;
            color: #fff;
            font-weight: 600;
            margin-bottom: 0.5rem;
            font-size: 0.9rem;
        }

        .form-group input,
        .form-group select,
        .form-group textarea {
            width: 100%;
            padding: 0.75rem 1rem;
            background: #171f2acc;
            border: 1px solid rgba(255,255,255,.2);
            border-radius: 8px;
            color: #fff;
            font-size: 1rem;
            transition: all 0.2s;
        }

        .form-group input:focus,
        .form-group select:focus,
        .form-group textarea:focus {
            outline: none;
            border-color: #4a90e2;
            box-shadow: 0 0 0 3px rgba(74,144,226,.1);
        }

        .form-group textarea {
            resize: vertical;
            min-height: 80px;
        }

        .option-group {
            margin-bottom: 1.5rem;
            background: rgba(255,255,255,.05);
            border-radius: 12px;
            padding: 1rem;
        }

        .option-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 0.75rem;
        }

        .option-number {
            background: #4a90e2;
            color: #fff;
            width: 2rem;
            height: 2rem;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            font-size: 0.9rem;
        }

        .correct-radio {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            cursor: pointer;
        }

        .correct-radio input[type="radio"] {
            width: auto;
            margin: 0;
        }

        .radio-label {
            color: #fff;
            font-weight: 600;
            font-size: 0.9rem;
        }

        .options-controls {
            display: flex;
            gap: 1rem;
            margin-top: 1rem;
        }

        .form-actions {
            display: flex;
            gap: 1rem;
            justify-content: flex-end;
            margin-top: 2rem;
            padding-top: 2rem;
            border-top: 1px solid rgba(255,255,255,.1);
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
    </style>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.getElementById('questionForm');
            const optionsContainer = document.getElementById('optionsContainer');
            const addOptionBtn = document.getElementById('addOption');
            const removeOptionBtn = document.getElementById('removeOption');
            
            let optionCount = 4;

            // Add option
            addOptionBtn.addEventListener('click', function() {
                if (optionCount >= 6) {
                    alert('Maximum 6 options autorisées');
                    return;
                }
                
                const optionGroup = document.createElement('div');
                optionGroup.className = 'option-group';
                optionGroup.innerHTML = `
                    <div class="option-header">
                        <span class="option-number">${optionCount + 1}</span>
                        <label class="correct-radio">
                            <input type="radio" name="correct_answer" value="${optionCount}">
                            <span class="radio-label">Bonne Réponse</span>
                        </label>
                    </div>
                    <textarea name="options[${optionCount}][text]" placeholder="Entrez l'option ${optionCount + 1}..." required></textarea>
                `;
                
                optionsContainer.appendChild(optionGroup);
                optionCount++;
                
                if (optionCount >= 6) {
                    addOptionBtn.disabled = true;
                }
                removeOptionBtn.disabled = false;
            });

            // Remove option
            removeOptionBtn.addEventListener('click', function() {
                if (optionCount <= 2) {
                    alert('Minimum 2 options requises');
                    return;
                }
                
                const lastOption = optionsContainer.lastElementChild;
                optionsContainer.removeChild(lastOption);
                optionCount--;
                
                if (optionCount <= 2) {
                    removeOptionBtn.disabled = true;
                }
                addOptionBtn.disabled = false;
            });

            // Form submission
            form.addEventListener('submit', function(e) {
                e.preventDefault();
                
                const formData = new FormData(form);
                const data = {
                    topic_id: formData.get('topic_id'),
                    question_text: formData.get('question_text'),
                    difficulty_level: formData.get('difficulty_level'),
                    question_type: formData.get('question_type'),
                    options: []
                };
                
                // Build options array
                for (let i = 0; i < optionCount; i++) {
                    const text = formData.get(`options[${i}][text]`);
                    const isCorrect = formData.get('correct_answer') == i;
                    
                    if (text.trim()) {
                        data.options.push({
                            text: text.trim(),
                            is_correct: isCorrect
                        });
                    }
                }
                
                // Validate
                if (data.options.length < 2) {
                    alert('Veuillez fournir au moins 2 options');
                    return;
                }
                
                if (!data.options.some(opt => opt.is_correct)) {
                    alert('Veuillez sélectionner une bonne réponse');
                    return;
                }
                
                // Submit
                fetch('/api/admin/questions', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify(data)
                })
                .then(response => response.json())
                .then(result => {
                    if (result.error) {
                        alert('Erreur: ' + result.error);
                    } else {
                        alert('Question créée avec succès!');
                        window.location.href = '/admin/questions';
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Une erreur est survenue lors de l\'enregistrement de la question');
                });
            });

            // Initialize
            removeOptionBtn.disabled = true;
        });
    </script>
</body>
</html> 
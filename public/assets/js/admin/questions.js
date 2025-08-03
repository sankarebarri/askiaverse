/**
 * Questions Management JavaScript - Askiaverse Admin
 */

class QuestionsManager {
    constructor() {
        this.init();
    }

    init() {
        this.setupEventListeners();
    }

    setupEventListeners() {
        // Add question form functionality
        const questionForm = document.getElementById('questionForm');
        if (questionForm) {
            this.setupQuestionForm(questionForm);
        }

        // Question list functionality
        this.setupQuestionList();
    }

    setupQuestionForm(form) {
        const optionsContainer = document.getElementById('optionsContainer');
        const addOptionBtn = document.getElementById('addOption');
        const removeOptionBtn = document.getElementById('removeOption');
        
        let optionCount = 4;

        // Add option
        if (addOptionBtn) {
            addOptionBtn.addEventListener('click', () => {
                if (optionCount >= 6) {
                    adminCore.showNotification('Maximum 6 options autorisées', 'warning');
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
                if (removeOptionBtn) {
                    removeOptionBtn.disabled = false;
                }
            });
        }

        // Remove option
        if (removeOptionBtn) {
            removeOptionBtn.addEventListener('click', () => {
                if (optionCount <= 2) {
                    adminCore.showNotification('Minimum 2 options requises', 'warning');
                    return;
                }
                
                const lastOption = optionsContainer.lastElementChild;
                optionsContainer.removeChild(lastOption);
                optionCount--;
                
                if (optionCount <= 2) {
                    removeOptionBtn.disabled = true;
                }
                if (addOptionBtn) {
                    addOptionBtn.disabled = false;
                }
            });
        }

        // Form submission
        form.addEventListener('submit', async (e) => {
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
                adminCore.showNotification('Veuillez fournir au moins 2 options', 'error');
                return;
            }
            
            if (!data.options.some(opt => opt.is_correct)) {
                adminCore.showNotification('Veuillez sélectionner une bonne réponse', 'error');
                return;
            }
            
            // Submit
            try {
                const result = await adminCore.apiRequest('/api/admin/questions', {
                    method: 'POST',
                    body: JSON.stringify(data)
                });
                
                adminCore.showNotification('Question créée avec succès!', 'success');
                setTimeout(() => {
                    window.location.href = '/admin/questions';
                }, 1500);
                
            } catch (error) {
                console.error('Error:', error);
                adminCore.showNotification('Une erreur est survenue lors de l\'enregistrement de la question', 'error');
            }
        });

        // Initialize
        if (removeOptionBtn) {
            removeOptionBtn.disabled = true;
        }
    }

    setupQuestionList() {
        // Handle question actions (edit, preview, delete)
        const questionCards = document.querySelectorAll('.question-card');
        
        questionCards.forEach(card => {
            const editBtn = card.querySelector('button:contains("Modifier")');
            const previewBtn = card.querySelector('button:contains("Aperçu")');
            const deleteBtn = card.querySelector('button:contains("Supprimer")');
            
            if (editBtn) {
                editBtn.addEventListener('click', () => {
                    // TODO: Implement edit functionality
                    adminCore.showNotification('Fonctionnalité de modification à venir', 'info');
                });
            }
            
            if (previewBtn) {
                previewBtn.addEventListener('click', () => {
                    // TODO: Implement preview functionality
                    adminCore.showNotification('Fonctionnalité d\'aperçu à venir', 'info');
                });
            }
            
            if (deleteBtn) {
                deleteBtn.addEventListener('click', async () => {
                    const confirmed = confirm('Êtes-vous sûr de vouloir supprimer cette question ?');
                    if (confirmed) {
                        // TODO: Implement delete functionality
                        adminCore.showNotification('Fonctionnalité de suppression à venir', 'info');
                    }
                });
            }
        });
    }
}

// Initialize questions manager when DOM is loaded
document.addEventListener('DOMContentLoaded', () => {
    new QuestionsManager();
}); 
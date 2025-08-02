<?php
$page_title = 'Tableau de bord - Askiaverse';
?>

<!-- Dashboard Content -->
<div class="dashboard-container">
    <!-- Class Selection Section -->
    <section class="class-selection">
        <h2 class="section-title">Choisis ta classe</h2>
        <div class="class-buttons">
            <button class="class-btn selected" data-class="6">6ème</button>
            <button class="class-btn" data-class="7">7ème</button>
        </div>
    </section>

    <!-- Challenges Section -->
    <section class="challenges-section">
        <h2 class="section-title">Défis pour la 6ème</h2>
        <div class="subject-cards">
            <!-- Mathematics Card -->
            <div class="subject-card" data-subject="mathematics">
                <div class="subject-icon">
                    <svg width="60" height="60" viewBox="0 0 60 60" fill="none">
                        <!-- Abacus frame -->
                        <rect x="10" y="15" width="40" height="30" rx="2" fill="#2A4D69" stroke="#4A90E2" stroke-width="2"/>
                        <!-- Horizontal bars -->
                        <line x1="15" y1="25" x2="45" y2="25" stroke="#4A90E2" stroke-width="1"/>
                        <line x1="15" y1="35" x2="45" y2="35" stroke="#4A90E2" stroke-width="1"/>
                        <!-- Beads -->
                        <circle cx="20" cy="25" r="3" fill="#FF6B35"/>
                        <circle cx="30" cy="25" r="3" fill="#4A90E2"/>
                        <circle cx="40" cy="25" r="3" fill="#50C878"/>
                        <circle cx="25" cy="35" r="3" fill="#FF6B35"/>
                        <circle cx="35" cy="35" r="3" fill="#4A90E2"/>
                        <circle cx="45" cy="35" r="3" fill="#50C878"/>
                    </svg>
                </div>
                <h3 class="subject-title">Mathématiques</h3>
            </div>

            <!-- French Card -->
            <div class="subject-card" data-subject="french">
                <div class="subject-icon">
                    <svg width="60" height="60" viewBox="0 0 60 60" fill="none">
                        <!-- Book -->
                        <rect x="15" y="20" width="30" height="25" rx="2" fill="#4A90E2"/>
                        <rect x="17" y="22" width="26" height="21" rx="1" fill="#7BB3F0"/>
                        <!-- Book pages -->
                        <line x1="20" y1="28" x2="40" y2="28" stroke="#2A4D69" stroke-width="1"/>
                        <line x1="20" y1="32" x2="40" y2="32" stroke="#2A4D69" stroke-width="1"/>
                        <line x1="20" y1="36" x2="35" y2="36" stroke="#2A4D69" stroke-width="1"/>
                    </svg>
                </div>
                <h3 class="subject-title">Français</h3>
            </div>

            <!-- History-Geography Card -->
            <div class="subject-card" data-subject="history-geo">
                <div class="subject-icon">
                    <svg width="60" height="60" viewBox="0 0 60 60" fill="none">
                        <!-- Globe -->
                        <circle cx="30" cy="30" r="20" fill="#4A90E2" stroke="#2A4D69" stroke-width="2"/>
                        <circle cx="30" cy="30" r="18" fill="#7BB3F0"/>
                        <!-- Continents -->
                        <path d="M20 25 Q25 20 30 25 Q35 30 40 25" fill="#50C878"/>
                        <path d="M15 35 Q20 30 25 35 Q30 40 35 35" fill="#50C878"/>
                        <path d="M25 15 Q30 10 35 15 Q30 20 25 15" fill="#50C878"/>
                        <!-- Meridian lines -->
                        <path d="M30 10 L30 50" stroke="#2A4D69" stroke-width="1" opacity="0.5"/>
                        <path d="M10 30 L50 30" stroke="#2A4D69" stroke-width="1" opacity="0.5"/>
                    </svg>
                </div>
                <h3 class="subject-title">Histoire-Géo</h3>
                <div class="new-indicator"></div>
            </div>
        </div>
    </section>
</div>

<!-- How to Play Modal -->
<div id="how-to-play-modal" class="modal-overlay hidden">
    <div class="modal-content how-to-play-modal">
        <div class="modal-header">
            <h2 class="modal-title">Comment Jouer</h2>
            <button class="close-modal-btn" id="close-how-to-play">×</button>
        </div>
        
        <div class="modal-body">
            <div class="how-to-play-section">
                <h3 class="section-heading">Le But du Jeu</h3>
                <p class="section-text">
                    Votre mission est de répondre correctement aux questions pour gagner de l'expérience (XP) et des Askia Orbs. 
                    L'XP vous permet de monter de niveau, et les Orbs vous serviront à débloquer des récompenses dans le futur.
                </p>
            </div>
            
            <div class="how-to-play-section">
                <h3 class="section-heading">Le Système de Points</h3>
                <ul class="points-list">
                    <li>+10 XP pour chaque bonne réponse.</li>
                    <li>Bonus d'XP pour chaque seconde restante au chronomètre.</li>
                    <li>Bonus de série (Streak) pour plusieurs bonnes réponses d'affilée.</li>
                    <li>Des Askia Orbs 🟠 sont attribués pour une bonne performance sur l'ensemble du quiz (score > 70%).</li>
                </ul>
            </div>
            
            <div class="how-to-play-section">
                <h3 class="section-heading">Les Jetons Focus</h3>
                <p class="section-text">
                    Vous recevez 3 Jetons Focus chaque jour. Cliquez sur le bouton 🧘 pendant un quiz pour utiliser un jeton. 
                    Cela désactivera le chronomètre pour la question en cours, vous donnant tout le temps nécessaire pour réfléchir.
                </p>
            </div>
        </div>
    </div>
</div>

<script>
    // Class selection
    document.querySelectorAll('.class-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            // Remove selected class from all buttons
            document.querySelectorAll('.class-btn').forEach(b => b.classList.remove('selected'));
            // Add selected class to clicked button
            this.classList.add('selected');
            
            // Update challenges title
            const classNumber = this.dataset.class;
            document.querySelector('.challenges-section .section-title').textContent = `Défis pour la ${classNumber}ème`;
        });
    });

    // Subject card click
    document.querySelectorAll('.subject-card').forEach(card => {
        card.addEventListener('click', function() {
            const subject = this.dataset.subject;
            console.log('Selected subject:', subject);
            // Show theme selection modal
            showThemeModal(subject);
        });
    });

    // Theme selection modal
    function showThemeModal(subject) {
        const modal = document.createElement('div');
        modal.className = 'modal-overlay';
        modal.innerHTML = `
            <div class="modal-content theme-modal">
                <div class="modal-header">
                    <h2 class="modal-title">Choisir un Thème en ${getSubjectName(subject)}</h2>
                    <button class="close-modal-btn" onclick="closeThemeModal()">×</button>
                </div>
                <div class="modal-body">
                    <button class="theme-btn" data-theme="arithmetic" data-subject="${subject}">
                        Arithmétique Simple
                    </button>
                </div>
            </div>
        `;
        document.body.appendChild(modal);
        
        // Add click handler for theme button
        modal.querySelector('.theme-btn').addEventListener('click', function() {
            const theme = this.dataset.theme;
            const subject = this.dataset.subject;
            closeThemeModal();
            showGameModeModal(subject, theme);
        });
    }

    function closeThemeModal() {
        const modal = document.querySelector('.theme-modal').closest('.modal-overlay');
        if (modal) {
            modal.remove();
        }
    }

    function getSubjectName(subject) {
        const names = {
            'mathematics': 'Mathématiques',
            'french': 'Français',
            'history-geo': 'Histoire-Géo'
        };
        return names[subject] || subject;
    }

    // Game mode selection modal
    function showGameModeModal(subject, theme) {
        const modal = document.createElement('div');
        modal.className = 'modal-overlay';
        modal.innerHTML = `
            <div class="modal-content game-mode-modal">
                <div class="modal-header">
                    <h2 class="modal-title">${getThemeName(theme)}: Choisis ton Mode de Jeu</h2>
                    <button class="close-modal-btn" onclick="closeGameModeModal()">×</button>
                </div>
                <div class="modal-body">
                    <button class="game-mode-btn" data-mode="quick-quiz" data-subject="${subject}" data-theme="${theme}">
                        Quiz Rapide <span class="checkmark">✔️</span>
                    </button>
                </div>
            </div>
        `;
        document.body.appendChild(modal);
        
        // Add click handler for game mode button
        modal.querySelector('.game-mode-btn').addEventListener('click', function() {
            const mode = this.dataset.mode;
            const subject = this.dataset.subject;
            const theme = this.dataset.theme;
            closeGameModeModal();
            startQuiz(subject, theme, mode);
        });
    }

    function closeGameModeModal() {
        const modal = document.querySelector('.game-mode-modal').closest('.modal-overlay');
        if (modal) {
            modal.remove();
        }
    }

    function getThemeName(theme) {
        const names = {
            'arithmetic': 'Arithmétique Simple',
            'geometry': 'Géométrie',
            'algebra': 'Algèbre'
        };
        return names[theme] || theme;
    }

    function startQuiz(subject, theme, mode) {
        // Redirect to quiz page with all parameters
        window.location.href = `?page=quiz&subject=${subject}&theme=${theme}&mode=${mode}`;
    }

    // How to Play Modal
    const howToPlayBtn = document.getElementById('how-to-play-btn');
    const howToPlayModal = document.getElementById('how-to-play-modal');
    const closeHowToPlayBtn = document.getElementById('close-how-to-play');

    if (howToPlayBtn) {
        howToPlayBtn.addEventListener('click', function(e) {
            e.preventDefault();
            howToPlayModal.classList.remove('hidden');
        });
    }

    if (closeHowToPlayBtn) {
        closeHowToPlayBtn.addEventListener('click', function() {
            howToPlayModal.classList.add('hidden');
        });
    }

    // Close modal when clicking outside
    howToPlayModal.addEventListener('click', function(e) {
        if (e.target === this) {
            this.classList.add('hidden');
        }
    });

    // Close modal with Escape key
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape' && !howToPlayModal.classList.contains('hidden')) {
            howToPlayModal.classList.add('hidden');
        }
    });
</script> 
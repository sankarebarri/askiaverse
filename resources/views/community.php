<?php
$page_title = 'Communauté - Askiaverse';
?>
<!DOCTYPE html>
<html lang="fr" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $page_title ?></title>
    <link rel="stylesheet" href="/assets/app.css">
</head>
<body>
    <!-- Header with HUD -->
    <header class="main-header">
        <div class="header-content">
            <a href="/" class="logo">Askiaverse</a>
            <div class="hud">
                <div class="hud-item" title="Niveau">LVL <span id="level-stat">5</span></div>
                <div class="hud-item xp-bar-container" title="Expérience">
                    <div id="xp-bar" class="xp-bar" style="width: 75%"></div>
                </div>
                <div class="hud-item" title="Askia Orbs">🪙 <span id="orbs-stat">150</span></div>
                <div class="hud-item" title="Jetons Focus">🧘 <span id="focus-tokens-stat">3</span></div>
            </div>
            <div class="user-menu">
                <button id="user-menu-btn" class="user-menu-btn">
                    <div class="avatar-placeholder"></div>
                    <span id="username-display">TestUser</span>
                    <span class="arrow-down">▼</span>
                </button>
                <div id="user-dropdown" class="user-dropdown hidden">
                    <a href="#" class="dropdown-item disabled">Profil (bientôt)</a>
                    <a href="#" class="dropdown-item disabled">Paramètres (bientôt)</a>
                    <a href="#" id="how-to-play-btn" class="dropdown-item">Comment Jouer</a>
                    <hr>
                    <a href="/logout" id="logout-button" class="dropdown-item">Se Déconnecter</a>
                </div>
            </div>
        </div>
    </header>

    <!-- Navigation -->
    <nav class="main-nav">
        <a href="/dashboard" class="nav-link">📚 Apprendre</a>
        <a href="/competition" class="nav-link">🏆 Compétition</a>
        <a href="/community" class="nav-link active">👥 Communauté</a>
    </nav>

    <!-- Main Content -->
    <main class="container py-8">
        <!-- Community Content -->
        <div class="community-container">
    <!-- Page Header -->
    <div class="community-header">
        <h1 class="community-title">Les Érudits du Sahel</h1>
        <p class="community-subtitle">Travaillez ensemble pour accomplir de grandes choses!</p>
    </div>
    
    <!-- Main Content Grid -->
    <div class="community-grid">
        <!-- Left Panel - Weekly Quest -->
        <div class="quest-panel">
            <h2 class="panel-title">Quête de la Semaine</h2>
            <div class="quest-content">
                <h3 class="quest-name">Maîtrise des Mathématiques</h3>
                <p class="quest-description">Répondez correctement à 500 questions de mathématiques en équipe cette semaine.</p>
                <div class="quest-progress">
                    <div class="progress-bar">
                        <div class="progress-fill" style="width: 65.4%"></div>
                    </div>
                    <div class="progress-text">327 / 500</div>
                </div>
            </div>
        </div>
        
        <!-- Middle Panel - Guild Members -->
        <div class="members-panel">
            <h2 class="panel-title">Membres de la Guilde</h2>
            <div class="members-list">
                <div class="member-item">
                    <div class="member-avatar"></div>
                    <div class="member-info">
                        <div class="member-name">hamza</div>
                        <div class="member-school">École Pilote, Gao</div>
                    </div>
                </div>
                <div class="member-item">
                    <div class="member-avatar"></div>
                    <div class="member-info">
                        <div class="member-name">Aicha</div>
                        <div class="member-school">Lycée Askia, Bamako</div>
                    </div>
                </div>
                <div class="member-item">
                    <div class="member-avatar"></div>
                    <div class="member-info">
                        <div class="member-name">Moussa</div>
                        <div class="member-school">École Liberté, Sikasso</div>
                    </div>
                </div>
                <div class="member-item">
                    <div class="member-avatar"></div>
                    <div class="member-info">
                        <div class="member-name">Fatoumata</div>
                        <div class="member-school">Gao International School, Gao</div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Right Panel - Message Board -->
        <div class="message-panel">
            <h2 class="panel-title">Tableau de Messages</h2>
            <div class="message-board">
                <div class="message-history">
                    <div class="message-item">
                        <div class="message-header">
                            <span class="message-author">hamza</span>
                            <span class="message-text">a dit:</span>
                        </div>
                        <div class="message-content">Super ! J'ai ajouté 20 points hier. On peut le faire !</div>
                    </div>
                    <div class="message-item">
                        <div class="message-header">
                            <span class="message-author">Moussa</span>
                            <span class="message-text">a dit:</span>
                        </div>
                        <div class="message-content">Oui ! Je vais faire quelques quiz d'algèbre maintenant.</div>
                    </div>
                    <div class="message-item">
                        <div class="message-header">
                            <span class="message-author">Aicha</span>
                            <span class="message-text">a dit:</span>
                        </div>
                        <div class="message-content">Bonjour à tous ! Prêts pour la quête de cette semaine?</div>
                    </div>
                </div>
                
                <div class="message-input-section">
                    <textarea 
                        class="message-input" 
                        placeholder="Écrivez un message à votre guilde..."
                        rows="3"
                    ></textarea>
                    <button class="send-button">Envoyer</button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    // Message sending functionality
    const messageInput = document.querySelector('.message-input');
    const sendButton = document.querySelector('.send-button');
    const messageHistory = document.querySelector('.message-history');

    function sendMessage() {
        const message = messageInput.value.trim();
        if (message) {
            // Create new message element
            const messageItem = document.createElement('div');
            messageItem.className = 'message-item';
            messageItem.innerHTML = `
                <div class="message-header">
                    <span class="message-author">hamza</span>
                    <span class="message-text">a dit:</span>
                </div>
                <div class="message-content">${message}</div>
            `;
            
            // Add to message history
            messageHistory.insertBefore(messageItem, messageHistory.firstChild);
            
            // Clear input
            messageInput.value = '';
            
            // Scroll to top
            messageHistory.scrollTop = 0;
        }
    }

    // Send message on button click
    sendButton.addEventListener('click', sendMessage);

    // Send message on Enter key (but allow Shift+Enter for new lines)
    messageInput.addEventListener('keydown', function(e) {
        if (e.key === 'Enter' && !e.shiftKey) {
            e.preventDefault();
            sendMessage();
        }
    });

    // Quest progress animation
    function animateProgress() {
        const progressFill = document.querySelector('.progress-fill');
        if (progressFill) {
            progressFill.style.transition = 'width 1s ease-in-out';
            progressFill.style.width = '65.4%';
        }
    }

    // Animate progress on page load
    setTimeout(animateProgress, 500);

    // User menu functionality
    document.getElementById('user-menu-btn').addEventListener('click', function() {
        this.classList.toggle('open');
        document.getElementById('user-dropdown').classList.toggle('hidden');
    });
    
    // Close dropdown when clicking outside
    document.addEventListener('click', function(e) {
        if (!e.target.closest('.user-menu')) {
            document.getElementById('user-menu-btn').classList.remove('open');
            document.getElementById('user-dropdown').classList.add('hidden');
        }
    });

    // Update HUD stats (placeholder - will be connected to backend)
    function updateHUD() {
        // This will be replaced with real data from the backend
        document.getElementById('level-stat').textContent = '5';
        document.getElementById('orbs-stat').textContent = '150';
        document.getElementById('focus-tokens-stat').textContent = '3';
        document.getElementById('xp-bar').style.width = '75%';
    }

    // Initialize HUD
    updateHUD();
</script>
        </div>
    </main>
</body>
</html> 
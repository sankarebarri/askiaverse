<?php
$page_title = 'Quiz - Askiaverse';
$subject = $subject ?? '';
$theme = $theme ?? '';
$mode = $mode ?? '';
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
        <a href="/community" class="nav-link">👥 Communauté</a>
    </nav>

    <!-- Main Content -->
    <main class="container py-8">
        <!-- Quiz Content -->
        <div class="quiz-container">
            <div class="quiz-header">
                <h1 class="quiz-title">Quiz: <?= htmlspecialchars(ucfirst($subject)) ?> - <?= htmlspecialchars(ucfirst($theme)) ?></h1>
                <p class="quiz-subtitle">Mode: <?= htmlspecialchars(ucfirst($mode)) ?></p>
            </div>
            
            <div class="quiz-game-area">
                <div class="quiz-info">
                    <div class="quiz-stats">
                        <div class="stat-item">
                            <span class="stat-label">Question</span>
                            <span class="stat-value" id="question-number">1</span>
                            <span class="stat-separator">/</span>
                            <span class="stat-total" id="total-questions">10</span>
                        </div>
                        <div class="stat-item">
                            <span class="stat-label">Score</span>
                            <span class="stat-value" id="current-score">0</span>
                        </div>
                        <div class="stat-item">
                            <span class="stat-label">Temps</span>
                            <span class="stat-value" id="timer">30</span>
                        </div>
                    </div>
                    <div class="timer-bar-container">
                        <div class="timer-bar" id="timer-bar"></div>
                    </div>
                </div>
                
                <div class="question-container">
                    <div class="question-text" id="question-text">
                        Chargement de la question...
                    </div>
                    
                    <div class="answer-options" id="answer-options">
                        <!-- Les options de réponse seront générées dynamiquement -->
                    </div>
                </div>
                
                <div class="quiz-controls">
                    <button class="focus-token-btn" id="focus-token-btn" title="Utiliser un jeton focus">
                        🧘 Focus
                    </button>
                    <button class="pause-btn" id="pause-btn" title="Mettre en pause">
                        ⏸️ Pause
                    </button>
                </div>
            </div>
        </div>
    </main>

    <!-- Pause Modal -->
    <div id="pause-modal" class="modal-overlay hidden">
        <div class="modal-content pause-modal">
            <div class="modal-header">
                <h2 class="modal-title">Quiz en Pause</h2>
                <button class="close-modal-btn" id="close-pause">×</button>
            </div>
            
            <div class="modal-body">
                <div class="pause-options">
                    <button class="pause-option-btn" id="resume-quiz">
                        <span class="option-icon">▶️</span>
                        <span class="option-text">Reprendre le Quiz</span>
                    </button>
                    <button class="pause-option-btn" id="restart-quiz">
                        <span class="option-icon">🔄</span>
                        <span class="option-text">Recommencer</span>
                    </button>
                    <button class="pause-option-btn" id="exit-quiz">
                        <span class="option-icon">🚪</span>
                        <span class="option-text">Quitter le Quiz</span>
                    </button>
                </div>
            </div>
        </div>
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
        // Quiz state
        let currentQuestion = 0;
        let score = 0;
        let timeLeft = 30;
        let maxTime = 30;
        let timerInterval;
        let questions = [];
        let focusTokensUsed = 0;
        let isPaused = false;
        let autoNextTimeout;

        // Initialize quiz
        document.addEventListener('DOMContentLoaded', function() {
            initializeQuiz();
        });

        function initializeQuiz() {
            // Load questions from database
            loadQuestionsFromDatabase();
        }

        let currentQuizId = null;

        function loadQuestionsFromDatabase() {
            const subject = '<?= $subject ?>';
            const theme = '<?= $theme ?>';
            
            // Show loading state
            document.getElementById('question-text').textContent = 'Chargement des questions...';
            document.getElementById('answer-options').innerHTML = '<div class="loading">Chargement...</div>';
            
            fetch(`/api/quiz/questions?subject=${encodeURIComponent(subject)}&theme=${encodeURIComponent(theme)}`)
                .then(response => response.json())
                .then(data => {
                    if (data.error) {
                        console.error('Error loading questions:', data.error);
                        // Fallback to sample questions
                        questions = generateSampleQuestions();
                    } else {
                        // Store quiz ID for later use
                        currentQuizId = data.quiz_id;
                        
                        // Convert database format to quiz format
                        questions = data.questions.map(q => ({
                            id: q.id,
                            question: q.text,
                            options: q.options.map(opt => opt.text),
                            correct: q.options.findIndex(opt => opt.is_correct)
                        }));
                    }
                    
                    if (questions.length === 0) {
                        questions = generateSampleQuestions();
                    }
                    
                    displayQuestion();
                    startTimer();
                    updateHUD();
                })
                .catch(error => {
                    console.error('Error loading questions:', error);
                    // Fallback to sample questions
                    questions = generateSampleQuestions();
                    displayQuestion();
                    startTimer();
                    updateHUD();
                });
        }

        function generateSampleQuestions() {
            const subject = '<?= $subject ?>';
            const theme = '<?= $theme ?>';
            
            // Sample questions based on subject and theme
            if (subject === 'mathematics' && theme === 'arithmetic') {
                return [
                    {
                        question: "Quel est le résultat de 15 + 27 ?",
                        options: ["40", "42", "43", "41"],
                        correct: 1
                    },
                    {
                        question: "Combien font 8 × 7 ?",
                        options: ["54", "56", "58", "60"],
                        correct: 1
                    },
                    {
                        question: "Quel est le résultat de 100 ÷ 4 ?",
                        options: ["20", "25", "30", "35"],
                        correct: 1
                    }
                ];
            }
            
            // Default questions
            return [
                {
                    question: "Question 1: Test de connaissance",
                    options: ["Option A", "Option B", "Option C", "Option D"],
                    correct: 0
                },
                {
                    question: "Question 2: Test de connaissance",
                    options: ["Option A", "Option B", "Option C", "Option D"],
                    correct: 1
                }
            ];
        }

        function displayQuestion() {
            if (currentQuestion >= questions.length) {
                endQuiz();
                return;
            }

            const question = questions[currentQuestion];
            document.getElementById('question-text').textContent = question.question;
            document.getElementById('question-number').textContent = currentQuestion + 1;
            document.getElementById('total-questions').textContent = questions.length;

            const optionsContainer = document.getElementById('answer-options');
            optionsContainer.innerHTML = '';

            question.options.forEach((option, index) => {
                const button = document.createElement('button');
                button.className = 'answer-option';
                button.textContent = option;
                button.dataset.index = index;
                button.addEventListener('click', () => selectAnswer(index));
                optionsContainer.appendChild(button);
            });

            // Reset timer
            timeLeft = maxTime;
            document.getElementById('timer').textContent = timeLeft;
            updateTimerBar();
            startTimer();
        }

        function selectAnswer(selectedIndex) {
            const question = questions[currentQuestion];
            const options = document.querySelectorAll('.answer-option');
            
            // Disable all options
            options.forEach(option => option.disabled = true);
            
            // Show correct/incorrect
            options.forEach((option, index) => {
                if (index === question.correct) {
                    option.classList.add('correct');
                } else if (index === selectedIndex && index !== question.correct) {
                    option.classList.add('incorrect');
                }
            });

            // Update score
            if (selectedIndex === question.correct) {
                score += 10;
                document.getElementById('current-score').textContent = score;
            }
            
            // Stop timer
            clearInterval(timerInterval);
            
            // Auto-advance to next question after 2 seconds
            autoNextTimeout = setTimeout(() => {
                currentQuestion++;
                displayQuestion();
            }, 2000);
        }

        function startTimer() {
            if (isPaused) return;
            
            clearInterval(timerInterval);
            timerInterval = setInterval(() => {
                if (!isPaused) {
                    timeLeft--;
                    document.getElementById('timer').textContent = timeLeft;
                    updateTimerBar();
                    
                    if (timeLeft <= 0) {
                        clearInterval(timerInterval);
                        // Auto-select wrong answer or skip
                        const options = document.querySelectorAll('.answer-option');
                        if (options.length > 0) {
                            selectAnswer(-1); // No correct answer selected
                        }
                    }
                }
            }, 1000);
        }

        function updateTimerBar() {
            const timerBar = document.getElementById('timer-bar');
            const percentage = (timeLeft / maxTime) * 100;
            timerBar.style.width = percentage + '%';
            
            // Change color based on time remaining
            if (percentage > 60) {
                timerBar.style.backgroundColor = '#50c878';
            } else if (percentage > 30) {
                timerBar.style.backgroundColor = '#ffa500';
            } else {
                timerBar.style.backgroundColor = '#ff6b35';
            }
        }

        function endQuiz() {
            // Submit results to database
            submitQuizResults();
            
            const container = document.querySelector('.quiz-game-area');
            container.innerHTML = `
                <div class="quiz-results">
                    <h2 class="results-title">Quiz Terminé!</h2>
                    <div class="results-stats">
                        <div class="result-item">
                            <span class="result-label">Score Final:</span>
                            <span class="result-value">${score}</span>
                        </div>
                        <div class="result-item">
                            <span class="result-label">Questions Répondues:</span>
                            <span class="result-value">${questions.length}</span>
                        </div>
                        <div class="result-item">
                            <span class="result-label">Pourcentage:</span>
                            <span class="result-value">${Math.round((score / (questions.length * 10)) * 100)}%</span>
                        </div>
                    </div>
                    <div class="results-actions">
                        <button class="btn-primary" onclick="window.location.href='/dashboard'">Retour au Tableau de Bord</button>
                        <button class="btn-secondary" onclick="location.reload()">Recommencer</button>
                    </div>
                </div>
            `;
        }

        function submitQuizResults() {
            // Use the stored quiz ID
            if (!currentQuizId) {
                console.error('No quiz ID available');
                return;
            }
            
            fetch('/api/quiz/submit', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({
                    quiz_id: currentQuizId,
                    score: score,
                    total_questions: questions.length
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data && data.message) {
                    console.log('Quiz results submitted:', data);
                    // Update HUD with new XP and orbs if available
                    if (data.xp_earned > 0 || data.orbs_earned > 0) {
                        // You could show a notification here
                        console.log(`Earned ${data.xp_earned} XP and ${data.orbs_earned} orbs!`);
                    }
                }
            })
            .catch(error => {
                console.error('Error submitting quiz results:', error);
            });
        }

        // Pause button
        document.getElementById('pause-btn').addEventListener('click', function() {
            pauseQuiz();
        });

        // Focus token button
        document.getElementById('focus-token-btn').addEventListener('click', function() {
            if (focusTokensUsed < 3) {
                focusTokensUsed++;
                clearInterval(timerInterval);
                this.textContent = `🧘 Focus (${3 - focusTokensUsed})`;
                this.disabled = true;
                updateHUD();
            }
        });

        // Pause modal functionality
        function pauseQuiz() {
            isPaused = true;
            clearInterval(timerInterval);
            document.getElementById('pause-modal').classList.remove('hidden');
        }

        function resumeQuiz() {
            isPaused = false;
            document.getElementById('pause-modal').classList.add('hidden');
            startTimer();
        }

        function restartQuiz() {
            isPaused = false;
            currentQuestion = 0;
            score = 0;
            focusTokensUsed = 0;
            clearInterval(timerInterval);
            clearTimeout(autoNextTimeout);
            document.getElementById('pause-modal').classList.add('hidden');
            document.getElementById('focus-token-btn').textContent = '🧘 Focus';
            document.getElementById('focus-token-btn').disabled = false;
            displayQuestion();
            updateHUD();
        }

        function exitQuiz() {
            window.location.href = '/dashboard';
        }

        // Pause modal buttons
        document.getElementById('resume-quiz').addEventListener('click', resumeQuiz);
        document.getElementById('restart-quiz').addEventListener('click', restartQuiz);
        document.getElementById('exit-quiz').addEventListener('click', exitQuiz);
        document.getElementById('close-pause').addEventListener('click', resumeQuiz);

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

        // Update HUD stats
        function updateHUD() {
            document.getElementById('level-stat').textContent = '5';
            document.getElementById('orbs-stat').textContent = '150';
            document.getElementById('focus-tokens-stat').textContent = (3 - focusTokensUsed).toString();
            document.getElementById('xp-bar').style.width = '75%';
        }

        // Initialize HUD
        updateHUD();

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
</body>
</html>
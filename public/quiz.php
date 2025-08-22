<?php
// Set session directory to a writable location
ini_set('session.save_path', '/tmp');
session_start();

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

// Get theme ID from URL
$themeId = $_GET['theme_id'] ?? null;
if (!$themeId) {
    header('Location: simple-subjects.php');
    exit;
}

// Load environment variables
require_once __DIR__ . '/../vendor/autoload.php';
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/..');
$dotenv->load();

// Database connection using environment variables
$host = $_ENV['DB_HOST'] ?? 'localhost';
$port = $_ENV['DB_PORT'] ?? 3306;
$dbname = $_ENV['DB_DATABASE'] ?? 'u379844049_askiagames_db';
$username_db = $_ENV['DB_USERNAME'] ?? 'u379844049_askiagames';
$password_db = $_ENV['DB_PASSWORD'] ?? '';

try {
    $dsn = "mysql:host={$host};port={$port};dbname={$dbname};charset=utf8mb4";
    $options = [
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES   => false,
    ];
    $pdo = new PDO($dsn, $username_db, $password_db, $options);
    
    // Get theme details
    $sql = "SELECT t.*, s.name as subject_name, s.color as subject_color 
            FROM themes t 
            JOIN subjects s ON t.subject_id = s.id 
            WHERE t.id = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$themeId]);
    $theme = $stmt->fetch();
    
    if (!$theme) {
        header('Location: simple-subjects.php');
        exit;
    }
    
    // Get questions for this theme
    $sql = "SELECT * FROM questions WHERE theme_id = ? ORDER BY RAND() LIMIT 10";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$themeId]);
    $questions = $stmt->fetchAll();
    
    if (empty($questions)) {
        $error = "Aucune question disponible pour ce thème.";
    }
    
} catch (Exception $e) {
    $error = 'Erreur de connexion à la base de données: ' . $e->getMessage();
}
?>
<!DOCTYPE html>
<html lang="fr" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quiz - <?= htmlspecialchars($theme['name'] ?? 'Thème') ?> - Askiaverse</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gradient-to-br from-blue-50 via-indigo-50 to-purple-50 min-h-screen">
    <!-- Header -->
    <?php include 'components/header.php'; ?>

    <!-- Main Content -->
    <main class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <?php if (isset($error)): ?>
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-6">
            <strong>Erreur:</strong> <?= htmlspecialchars($error) ?>
        </div>
        <?php elseif (isset($theme) && !empty($questions)): ?>
        
        <!-- Quiz Header -->
        <div class="bg-white rounded-2xl shadow-lg p-8 mb-8">
            <div class="text-center">
                <div class="inline-flex items-center justify-center w-20 h-20 rounded-full mb-4" 
                     style="background: linear-gradient(135deg, <?= $theme['subject_color'] ?>20, <?= $theme['subject_color'] ?>10);">
                    <span class="text-3xl">🎯</span>
                </div>
                <h1 class="text-3xl font-bold text-gray-900 mb-2">Quiz: <?= htmlspecialchars($theme['name']) ?></h1>
                <p class="text-gray-600 mb-4"><?= htmlspecialchars($theme['description']) ?></p>
                <div class="flex items-center justify-center space-x-6 text-sm text-gray-500">
                    <span>📚 <?= htmlspecialchars($theme['subject_name']) ?></span>
                    <span>🎯 <?= count($questions) ?> questions</span>
                    <span>⭐ <?= ucfirst($theme['difficulty']) ?></span>
                </div>
            </div>
        </div>

        <!-- Quiz Container -->
        <div id="quiz-container" class="space-y-6">
            <!-- Progress Bar -->
            <div class="bg-white rounded-xl shadow-lg p-6">
                <div class="flex justify-between items-center mb-4">
                    <div class="text-lg font-medium text-gray-700">
                        Question <span id="current-question">1</span> sur <?= count($questions) ?>
                    </div>
                    <div class="flex items-center space-x-4">
                        <div class="text-sm text-gray-600">
                            <span id="answered-count">0</span> / <?= count($questions) ?> répondues
                        </div>
                        <div class="flex items-center space-x-2">
                            <span class="text-sm text-gray-600">⏱️</span>
                            <div class="w-24 bg-gray-200 rounded-full h-2">
                                <div id="timer-bar" class="bg-green-500 h-2 rounded-full transition-all duration-1000" style="width: 100%"></div>
                            </div>
                            <span id="timer-text" class="text-sm font-medium text-gray-700">20s</span>
                        </div>
                    </div>
                </div>
                <div class="w-full bg-gray-200 rounded-full h-3">
                    <div id="progress-bar" class="bg-blue-600 h-3 rounded-full transition-all duration-300" style="width: 0%"></div>
                </div>
            </div>

            <!-- Question Display -->
            <div id="question-display" class="bg-white rounded-xl shadow-lg p-8">
                <!-- Question content will be loaded here -->
            </div>

            <!-- Navigation -->
            <div class="bg-white rounded-xl shadow-lg p-6">
                <div class="flex justify-between items-center">
                    <button id="prev-btn" class="px-6 py-3 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors duration-200 disabled:opacity-50 disabled:cursor-not-allowed" disabled>
                        ← Précédent
                    </button>
                    
                    <div class="flex space-x-4">
                        <button id="reset-btn" class="px-6 py-3 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors duration-200">
                            🔄 Recommencer
                        </button>
                        <button id="next-btn" class="px-8 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors duration-200 font-medium">
                            Suivant →
                        </button>
                        <button id="finish-btn" class="px-8 py-3 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors duration-200 font-medium hidden">
                            🎯 Terminer le Quiz
                        </button>
                    </div>
                </div>
            </div>
        </div>
        
        <?php endif; ?>
    </main>

    <!-- Footer -->
    <footer class="bg-gray-900 text-white py-12 mt-20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <p class="text-gray-400">
                © 2024 Askiaverse. L'aventure du savoir commence ici.
            </p>
        </div>
    </footer>

    <script>
        // Quiz data
        const questions = <?= json_encode($questions) ?>;
        const totalQuestions = questions.length;
        let currentQuestionIndex = 0;
        let userAnswers = {};
        let timer = null;
        let timeLeft = 20;
        
        // DOM elements
        const questionDisplay = document.getElementById('question-display');
        const currentQuestionSpan = document.getElementById('current-question');
        const answeredCountSpan = document.getElementById('answered-count');
        const progressBar = document.getElementById('progress-bar');
        const timerBar = document.getElementById('timer-bar');
        const timerText = document.getElementById('timer-text');
        const prevBtn = document.getElementById('prev-btn');
        const nextBtn = document.getElementById('next-btn');
        const finishBtn = document.getElementById('finish-btn');
        const resetBtn = document.getElementById('reset-btn');
        
        // Initialize quiz
        function initQuiz() {
            currentQuestionIndex = 0;
            userAnswers = {};
            updateProgress();
            showQuestion(currentQuestionIndex);
            startTimer();
        }
        
        // Show question
        function showQuestion(index) {
            if (index < 0 || index >= totalQuestions) return;
            
            currentQuestionIndex = index;
            const question = questions[index];
            const isAnswered = userAnswers[question.id] !== undefined;
            
            // Update question display
            questionDisplay.innerHTML = `
                <div class="text-center mb-8">
                    <h2 class="text-2xl font-bold text-gray-900 mb-4">${question.question}</h2>
                    ${index === totalQuestions - 1 ? '<p class="text-blue-600 font-medium">🏁 Dernière question !</p>' : ''}
                </div>
                
                <div class="space-y-4">
                    ${JSON.parse(question.options).map((option, optionIndex) => `
                        <label class="flex items-center p-4 rounded-lg border-2 cursor-pointer transition-colors duration-200 ${
                            isAnswered && userAnswers[question.id] === optionIndex 
                                ? 'border-blue-500 bg-blue-50' 
                                : 'border-gray-200 hover:border-blue-300'
                        }">
                            <input 
                                type="radio" 
                                name="answer_${question.id}" 
                                value="${optionIndex}"
                                class="w-5 h-5 text-blue-600 border-gray-300 focus:ring-blue-500"
                                ${isAnswered ? 'disabled' : ''}
                                ${isAnswered && userAnswers[question.id] === optionIndex ? 'checked' : ''}
                            >
                            <span class="ml-4 text-lg text-gray-700">${option}</span>
                        </label>
                    `).join('')}
                </div>
            `;
            
            // Add event listeners to radio buttons
            const radioButtons = questionDisplay.querySelectorAll('input[type="radio"]');
            radioButtons.forEach(radio => {
                radio.addEventListener('change', function() {
                    userAnswers[question.id] = parseInt(this.value);
                    updateProgress();
                    // Don't reset timer when selecting answer - only when moving to next question
                });
            });
            
            // Update navigation
            updateNavigation();
            updateProgress();
        }
        
        // Update progress
        function updateProgress() {
            const answered = Object.keys(userAnswers).length;
            const progress = (answered / totalQuestions) * 100;
            
            currentQuestionSpan.textContent = currentQuestionIndex + 1;
            answeredCountSpan.textContent = answered;
            progressBar.style.width = progress + '%';
            
            // Show/hide finish button
            if (answered === totalQuestions) {
                finishBtn.classList.remove('hidden');
                nextBtn.classList.add('hidden');
            } else {
                finishBtn.classList.add('hidden');
                nextBtn.classList.remove('hidden');
            }
        }
        
        // Update navigation
        function updateNavigation() {
            prevBtn.disabled = currentQuestionIndex === 0;
            
            // Hide next button on last question
            if (currentQuestionIndex === totalQuestions - 1) {
                nextBtn.style.display = 'none';
            } else {
                nextBtn.style.display = 'inline-flex';
            }
        }
        
        // Timer functions
        function startTimer() {
            timeLeft = 20;
            updateTimerDisplay();
            
            timer = setInterval(() => {
                timeLeft--;
                updateTimerDisplay();
                
                if (timeLeft <= 0) {
                    clearInterval(timer);
                    autoAnswer();
                }
            }, 1000);
        }
        
        function resetTimer() {
            if (timer) {
                clearInterval(timer);
            }
        }
        
        function updateTimerDisplay() {
            const percentage = (timeLeft / 20) * 100;
            timerBar.style.width = percentage + '%';
            timerText.textContent = timeLeft + 's';
            
            // Change color based on time
            if (timeLeft > 10) {
                timerBar.className = 'bg-green-500 h-2 rounded-full transition-all duration-1000';
            } else if (timeLeft > 5) {
                timerBar.className = 'bg-yellow-500 h-2 rounded-full transition-all duration-1000';
            } else {
                timerBar.className = 'bg-red-500 h-2 rounded-full transition-all duration-1000';
            }
        }
        
        // Auto answer when time runs out
        function autoAnswer() {
            const currentQuestion = questions[currentQuestionIndex];
            if (userAnswers[currentQuestion.id] === undefined) {
                // Random answer if none selected
                const randomAnswer = Math.floor(Math.random() * JSON.parse(currentQuestion.options).length);
                userAnswers[currentQuestion.id] = randomAnswer;
                updateProgress();
                
                // Show auto-answer notification
                showAutoAnswerNotification();
            }
            
            // Automatically move to next question after auto-answer
            if (currentQuestionIndex < totalQuestions - 1) {
                // Move to next question
                setTimeout(() => {
                    currentQuestionIndex++;
                    showQuestion(currentQuestionIndex);
                    startTimer();
                }, 2000); // 2 second delay to show the auto-answer
            } else {
                // Last question - show finish button
                updateProgress();
            }
        }
        
        // Show auto-answer notification
        function showAutoAnswerNotification() {
            const notification = document.createElement('div');
            notification.className = 'fixed top-4 right-4 bg-yellow-100 border border-yellow-400 text-yellow-700 px-4 py-3 rounded z-50';
            notification.innerHTML = `
                <div class="flex items-center">
                    <span class="text-xl mr-2">⏰</span>
                    <span>Temps écoulé ! Réponse automatique sélectionnée.</span>
                </div>
            `;
            document.body.appendChild(notification);
            
            // Remove notification after 2 seconds
            setTimeout(() => {
                notification.remove();
            }, 2000);
        }
        
        // Navigation event listeners
        prevBtn.addEventListener('click', () => {
            if (currentQuestionIndex > 0) {
                resetTimer();
                showQuestion(currentQuestionIndex - 1);
                startTimer();
            }
        });
        
        nextBtn.addEventListener('click', () => {
            if (currentQuestionIndex < totalQuestions - 1) {
                resetTimer();
                showQuestion(currentQuestionIndex + 1);
                startTimer();
            }
        });
        
        // Add keyboard navigation
        document.addEventListener('keydown', (e) => {
            if (e.key === 'ArrowLeft' && currentQuestionIndex > 0) {
                prevBtn.click();
            } else if (e.key === 'ArrowRight' && currentQuestionIndex < totalQuestions - 1) {
                nextBtn.click();
            }
        });
        
        // Reset quiz
        resetBtn.addEventListener('click', function() {
            if (confirm('Êtes-vous sûr de vouloir recommencer le quiz ?')) {
                resetTimer();
                initQuiz();
            }
        });
        
        // Finish quiz
        finishBtn.addEventListener('click', function() {
            const answered = Object.keys(userAnswers).length;
            if (answered < totalQuestions) {
                alert(`Veuillez répondre à toutes les questions. Vous avez répondu à ${answered} sur ${totalQuestions} questions.`);
                return;
            }
            
            submitQuiz();
        });
        
        // Submit quiz function
        function submitQuiz() {
            const formData = new FormData();
            formData.append('theme_id', '<?= $themeId ?>');
            formData.append('total_questions', totalQuestions);
            
            // Add all user answers
            Object.keys(userAnswers).forEach(questionId => {
                formData.append(`answer_${questionId}`, userAnswers[questionId]);
            });
            
            // Show loading state
            finishBtn.textContent = '⏳ Calcul des résultats...';
            finishBtn.disabled = true;
            
            // Send quiz results
            fetch('quiz-results.php', {
                method: 'POST',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: formData
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error('Erreur réseau: ' + response.status);
                }
                return response.json();
            })
            .then(data => {
                if (data.success) {
                    // Show celebration screen with XP and orbs gained
                    showCelebrationScreen(data);
                } else {
                    throw new Error(data.message || 'Erreur inconnue');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Erreur lors de la soumission du quiz: ' + error.message);
                finishBtn.textContent = '🎯 Terminer le Quiz';
                finishBtn.disabled = false;
            });
        }
        
        // Show celebration screen
        function showCelebrationScreen(data) {
            const celebrationHTML = `
                <div class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
                    <div class="bg-white rounded-2xl p-8 max-w-md w-full mx-4 text-center">
                        <div class="text-6xl mb-4">🎉</div>
                        <h2 class="text-2xl font-bold text-gray-900 mb-4">Quiz Terminé !</h2>
                        
                        <div class="space-y-4 mb-6">
                            <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                                <div class="text-2xl text-blue-800 font-bold">
                                    +${data.xp_gained} XP
                                </div>
                                <div class="text-blue-600 text-sm">Expérience gagnée</div>
                            </div>
                            
                            <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4">
                                <div class="text-2xl text-yellow-800 font-bold">
                                    +${data.orbs_gained} 💎
                                </div>
                                <div class="text-yellow-600 text-sm">Orbes gagnés</div>
                            </div>
                        </div>
                        
                        <div class="text-sm text-gray-600 mb-6">
                            <div>XP Total: ${data.previous_xp} → ${data.new_total_xp}</div>
                            <div>Orbes Total: ${data.previous_orbs} → ${data.new_total_orbs}</div>
                        </div>
                        
                        <div class="flex space-x-4">
                            <button onclick="window.location.href='quiz-results.php?attempt_id=${data.attempt_id}'" 
                                    class="flex-1 bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition-colors duration-200">
                                Voir les Détails
                            </button>
                            <button onclick="window.location.href='user-dashboard.php'" 
                                    class="flex-1 bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700 transition-colors duration-200">
                                Tableau de Bord
                            </button>
                        </div>
                    </div>
                </div>
            `;
            
            document.body.insertAdjacentHTML('beforeend', celebrationHTML);
        }
        
        // Initialize quiz when page loads
        document.addEventListener('DOMContentLoaded', initQuiz);
    </script>
</body>
</html>

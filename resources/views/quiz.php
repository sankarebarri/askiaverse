<?php
$page_title = 'Quiz - Askiaverse';

// Get quiz parameters
$subject = $_GET['subject'] ?? 'mathematics';
$theme = $_GET['theme'] ?? 'arithmetic';
$mode = $_GET['mode'] ?? 'quick-quiz';

// Sample questions for demonstration
$questions = [
    [
        'question' => 'Combien font 9 - 4 ?',
        'options' => ['3', '4', '5', '6'],
        'correct' => 2
    ],
    [
        'question' => 'Combien font 6 × 3 ?',
        'options' => ['9', '12', '15', '18'],
        'correct' => 3
    ],
    [
        'question' => 'Combien font 8 + 7 ?',
        'options' => ['13', '14', '15', '16'],
        'correct' => 2
    ],
    [
        'question' => 'Combien font 20 ÷ 4 ?',
        'options' => ['3', '4', '5', '6'],
        'correct' => 2
    ],
    [
        'question' => 'Combien font 12 - 5 ?',
        'options' => ['5', '6', '7', '8'],
        'correct' => 2
    ]
];
?>

<!-- Quiz Game Board -->
<div class="quiz-container">
    <!-- Quiz Header -->
    <div class="quiz-header">
        <div class="quiz-logo">Askiaverse</div>
        <button class="pause-btn" id="pause-btn">|| Pause</button>
    </div>
    
    <!-- Quiz Card -->
    <div class="quiz-card">
        <!-- Progress and HUD -->
        <div class="quiz-hud">
            <div class="progress-section">
                <div class="question-counter">Question <span id="current-question">1</span>/<span id="total-questions">5</span></div>
                <div class="progress-bar">
                    <div class="progress-fill" id="progress-fill"></div>
                </div>
            </div>
            
            <div class="game-stats">
                <div class="focus-token">
                    <div class="token-icon">🧘</div>
                    <div class="token-count">3</div>
                </div>
                <div class="timer" id="timer">15</div>
            </div>
        </div>
        
        <!-- Question -->
        <div class="question-section">
            <h2 class="question-text" id="question-text">Combien font 9 - 4 ?</h2>
        </div>
        
        <!-- Answer Options -->
        <div class="answer-grid">
            <button class="answer-btn" data-index="0">3</button>
            <button class="answer-btn" data-index="1">4</button>
            <button class="answer-btn" data-index="2">5</button>
            <button class="answer-btn" data-index="3">6</button>
        </div>
    </div>
</div>

<!-- Pause Modal -->
<div id="pause-modal" class="modal-overlay hidden">
    <div class="modal-content pause-modal">
        <h2 class="modal-title">Jeu en Pause</h2>
        <div class="pause-buttons">
            <button class="pause-btn-primary" id="resume-btn">Reprendre</button>
            <button class="pause-btn-secondary" id="restart-btn">Recommencer</button>
            <button class="pause-btn-secondary" id="quit-btn">Quitter le Défi</button>
        </div>
    </div>
</div>

<script>
    // Quiz state
    let currentQuestionIndex = 0;
    let score = 0;
    let timeLeft = 15;
    let timerInterval;
    let isPaused = false;
    
    const questions = <?= json_encode($questions) ?>;
    
    // Initialize quiz
    function initQuiz() {
        updateQuestion();
        startTimer();
        updateProgress();
    }
    
    // Update question display
    function updateQuestion() {
        const question = questions[currentQuestionIndex];
        document.getElementById('question-text').textContent = question.question;
        document.getElementById('current-question').textContent = currentQuestionIndex + 1;
        document.getElementById('total-questions').textContent = questions.length;
        
        // Update answer buttons
        const answerBtns = document.querySelectorAll('.answer-btn');
        answerBtns.forEach((btn, index) => {
            btn.textContent = question.options[index];
            btn.className = 'answer-btn';
            btn.disabled = false;
        });
    }
    
    // Update progress bar
    function updateProgress() {
        const progress = ((currentQuestionIndex + 1) / questions.length) * 100;
        document.getElementById('progress-fill').style.width = progress + '%';
    }
    
    // Start timer
    function startTimer() {
        timeLeft = 15;
        updateTimerDisplay();
        
        timerInterval = setInterval(() => {
            if (!isPaused) {
                timeLeft--;
                updateTimerDisplay();
                
                if (timeLeft <= 0) {
                    clearInterval(timerInterval);
                    handleTimeout();
                }
            }
        }, 1000);
    }
    
    // Update timer display
    function updateTimerDisplay() {
        document.getElementById('timer').textContent = timeLeft;
    }
    
    // Handle timeout
    function handleTimeout() {
        // Auto-submit wrong answer
        submitAnswer(-1);
    }
    
    // Submit answer
    function submitAnswer(selectedIndex) {
        clearInterval(timerInterval);
        
        const question = questions[currentQuestionIndex];
        const isCorrect = selectedIndex === question.correct;
        
        if (isCorrect) {
            score += 10;
        }
        
        // Show feedback
        const answerBtns = document.querySelectorAll('.answer-btn');
        answerBtns.forEach((btn, index) => {
            btn.disabled = true;
            if (index === question.correct) {
                btn.classList.add('correct');
            } else if (index === selectedIndex && !isCorrect) {
                btn.classList.add('incorrect');
            }
        });
        
        // Move to next question after delay
        setTimeout(() => {
            currentQuestionIndex++;
            if (currentQuestionIndex < questions.length) {
                updateQuestion();
                updateProgress();
                startTimer();
            } else {
                endQuiz();
            }
        }, 1500);
    }
    
    // End quiz
    function endQuiz() {
        const finalScore = Math.round((score / (questions.length * 10)) * 100);
        alert(`Quiz terminé! Score: ${finalScore}%`);
        window.location.href = '?page=dashboard';
    }
    
    // Answer button click handlers
    document.querySelectorAll('.answer-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            const selectedIndex = parseInt(this.dataset.index);
            submitAnswer(selectedIndex);
        });
    });
    
    // Pause functionality
    document.getElementById('pause-btn').addEventListener('click', function() {
        isPaused = true;
        document.getElementById('pause-modal').classList.remove('hidden');
    });
    
    document.getElementById('resume-btn').addEventListener('click', function() {
        isPaused = false;
        document.getElementById('pause-modal').classList.add('hidden');
    });
    
    document.getElementById('restart-btn').addEventListener('click', function() {
        if (confirm('Êtes-vous sûr de vouloir recommencer le quiz?')) {
            window.location.reload();
        }
    });
    
    document.getElementById('quit-btn').addEventListener('click', function() {
        if (confirm('Êtes-vous sûr de vouloir quitter le quiz?')) {
            window.location.href = '?page=dashboard';
        }
    });
    
    // Close pause modal when clicking outside
    document.getElementById('pause-modal').addEventListener('click', function(e) {
        if (e.target === this) {
            isPaused = false;
            this.classList.add('hidden');
        }
    });
    
    // Initialize quiz on page load
    document.addEventListener('DOMContentLoaded', initQuiz);
</script> 
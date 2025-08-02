<?php
$page_title = 'Connexion - Askiaverse';
?>

<!-- Login Form -->
<div class="auth-container">
    <!-- Cosmic Background -->
    <div class="cosmic-background">
        <div class="stars"></div>
    </div>
    
    <!-- Login Card -->
    <div class="auth-card">
        <!-- Header -->
        <div class="auth-header">
            <div class="rocket-logo">
                <svg width="40" height="40" viewBox="0 0 40 40" fill="none">
                    <!-- Rocket body (red) -->
                    <path d="M20 5 L25 15 L20 35 L15 15 Z" fill="#FF4444"/>
                    <!-- Rocket nose (white) -->
                    <path d="M20 5 L22 12 L18 12 Z" fill="white"/>
                    <!-- Rocket fins (blue) -->
                    <path d="M15 15 L10 20 L12 25 L15 15 Z" fill="#4A90E2"/>
                    <path d="M25 15 L30 20 L28 25 L25 15 Z" fill="#4A90E2"/>
                    <!-- Window (white circle) -->
                    <circle cx="20" cy="20" r="3" fill="white"/>
                </svg>
            </div>
            <h1 class="auth-title">Askiaverse</h1>
        </div>
        
        <!-- Subtitle -->
        <p class="auth-subtitle">Connectez-vous pour continuer votre aventure.</p>
        
        <!-- Form -->
        <form id="login-form" class="auth-form">
            <div class="form-group">
                <label for="username" class="form-label">Nom d'utilisateur</label>
                <input 
                    type="text" 
                    id="username" 
                    name="username" 
                    required
                    class="form-input"
                    placeholder="Entrez votre nom d'utilisateur"
                >
            </div>
            
            <div class="form-group">
                <label for="password" class="form-label">Mot de passe</label>
                <input 
                    type="password" 
                    id="password" 
                    name="password" 
                    required
                    class="form-input"
                    placeholder="Entrez votre mot de passe"
                >
            </div>
            
            <button type="submit" class="auth-button">
                Se Connecter
            </button>
        </form>
        
        <!-- Registration Link -->
        <div class="auth-footer">
            <p class="auth-link-text">
                Pas encore de compte? 
                <a href="?page=register" class="auth-link">Inscrivez-vous</a>
            </p>
        </div>
    </div>
</div>

<script>
    // Form submission
    document.getElementById('login-form').addEventListener('submit', function(e) {
        e.preventDefault();
        
        const formData = new FormData(this);
        
        // Simulate login (replace with actual backend call)
        console.log('Login attempt:', Object.fromEntries(formData));
        
        // For demo purposes, redirect to dashboard
        window.location.href = '?page=dashboard';
    });
</script> 
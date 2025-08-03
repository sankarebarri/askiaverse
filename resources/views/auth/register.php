<?php
$page_title = 'Inscription - Askiaverse';
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
<!-- Registration Form -->
<div class="auth-container">
    <!-- Cosmic Background -->
    <div class="cosmic-background">
        <div class="stars"></div>
    </div>
    
    <!-- Registration Card -->
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
        <p class="auth-subtitle">Créez votre compte pour commencer à apprendre.</p>
        
        <!-- Form -->
        <form id="register-form" class="auth-form">
            <div class="form-group">
                <label for="username" class="form-label">Nom d'utilisateur</label>
                <input 
                    type="text" 
                    id="username" 
                    name="username" 
                    required
                    class="form-input"
                    placeholder="Choisissez un nom d'utilisateur"
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
                    placeholder="Créez un mot de passe"
                >
            </div>
            
            <div class="form-group">
                <label for="password_confirm" class="form-label">Confirmez le mot de passe</label>
                <input 
                    type="password" 
                    id="password_confirm" 
                    name="password_confirm" 
                    required
                    class="form-input"
                    placeholder="Confirmez votre mot de passe"
                >
            </div>
            
            <div class="form-group">
                <label for="class" class="form-label">Votre classe actuelle</label>
                <select id="class" name="class" required class="form-input">
                    <option value="">-- Choisissez votre classe --</option>
                    <option value="6eme">6ème</option>
                    <option value="5eme">5ème</option>
                    <option value="4eme">4ème</option>
                    <option value="3eme">3ème</option>
                    <option value="2nde">2nde</option>
                    <option value="1ere">1ère</option>
                    <option value="terminale">Terminale</option>
                </select>
            </div>
            
            <div class="form-group">
                <label for="city" class="form-label">Ville</label>
                <input 
                    type="text" 
                    id="city" 
                    name="city" 
                    required
                    class="form-input"
                    placeholder="Votre ville"
                >
            </div>
            
            <div class="form-group">
                <label for="school" class="form-label">École</label>
                <input 
                    type="text" 
                    id="school" 
                    name="school" 
                    required
                    class="form-input"
                    placeholder="Nom de votre école"
                >
            </div>
            
            <button type="submit" class="auth-button">
                Créer le Compte
            </button>
        </form>
        
        <!-- Login Link -->
        <div class="auth-footer">
            <p class="auth-link-text">
                Déjà un compte? 
                <a href="/login" class="auth-link">Se connecter</a>
            </p>
        </div>
    </div>
</div>

<script>
    // Form submission
    document.getElementById('register-form').addEventListener('submit', function(e) {
        e.preventDefault();
        
        const formData = new FormData(this);
        
        // Basic validation
        if (formData.get('password') !== formData.get('password_confirm')) {
            alert('Les mots de passe ne correspondent pas');
            return;
        }
        
        if (formData.get('password').length < 6) {
            alert('Le mot de passe doit contenir au moins 6 caractères');
            return;
        }
        
        // Show loading state
        const submitBtn = this.querySelector('button[type="submit"]');
        const originalText = submitBtn.textContent;
        submitBtn.textContent = 'Création...';
        submitBtn.disabled = true;
        
        // Prepare registration data
        const registrationData = {
            username: formData.get('username'),
            password: formData.get('password'),
            class: formData.get('class'),
            city: formData.get('city'),
            school: formData.get('school')
        };
        
        // Send registration request to backend
        fetch('/register', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify(registrationData)
        })
        .then(response => response.json())
        .then(data => {
            if (data.message === 'Utilisateur enregistré avec succès.') {
                // Success - redirect to login page
                alert('Compte créé avec succès! Vous pouvez maintenant vous connecter.');
                window.location.href = '/login';
            } else {
                // Show error message
                alert(data.message || 'Erreur lors de la création du compte');
                submitBtn.textContent = originalText;
                submitBtn.disabled = false;
            }
        })
        .catch(error => {
            console.error('Registration error:', error);
            alert('Erreur lors de la création du compte. Veuillez réessayer.');
            submitBtn.textContent = originalText;
            submitBtn.disabled = false;
        });
    });
</script>
</body>
</html> 
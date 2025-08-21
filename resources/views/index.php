<?php
$page_title = 'Askiaverse - Apprendre en s\'amusant';
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
<!-- Header for Index Page -->
<header class="index-header">
    <div class="header-content">
        <div class="logo-section">
            <h1 class="logo">Askiaverse</h1>
            <div class="constellation-graphic"></div>
        </div>
        <div class="auth-buttons">
                            <a href="/login.php" class="btn btn-outline">Se Connecter</a>
                <a href="/register.php" class="btn btn-primary">Créer un Compte</a>
        </div>
    </div>
</header>

<!-- Hero Section -->
<section class="hero-section">
    <!-- Animated Background -->
    <div class="cosmic-background">
        <div class="stars"></div>
        <div class="constellation-top-left"></div>
    </div>
    
    <!-- Main Content -->
    <div class="hero-content">
        <h1 class="hero-headline">
            L'aventure du savoir commence ici.
        </h1>
        <p class="hero-description">
            Plongez dans un univers de jeux éducatifs conçus pour les jeunes esprits brillants du Mali.
        </p>
        
        <!-- CTA Buttons -->
        <div class="cta-section">
            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                <a href="/subjects" class="btn btn-outline">
                    📚 Découvrir les Matières
                </a>
                <a href="/register" class="btn btn-cta">
                    🚀 Essayer un Défi Gratuit!
                </a>
            </div>
        </div>
    </div>
</section>
</body>
</html> 
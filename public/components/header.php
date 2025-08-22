<?php
// Set session directory to a writable location
ini_set('session.save_path', '/tmp');
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<header class="bg-white shadow-sm border-b">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center py-4">
            <!-- Left: Askiaverse Logo -->
            <div class="flex items-center">
                <?php if (isset($_SESSION['user_id'])): ?>
                    <a href="user-dashboard.php" class="text-2xl font-bold text-gray-900 hover:text-blue-600 transition-colors duration-200">Askiaverse</a>
                <?php else: ?>
                    <a href="index.php" class="text-2xl font-bold text-gray-900 hover:text-blue-600 transition-colors duration-200">Askiaverse</a>
                <?php endif; ?>
            </div>
            
            <!-- Right: Navigation Menu -->
            <nav class="flex space-x-3">
                <?php if (isset($_SESSION['user_id'])): ?>
                    <!-- Logged in user menu -->
                    <a href="user-dashboard.php" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition-colors duration-200 font-medium">
                        Tableau de Bord
                    </a>
                    <a href="simple-subjects.php" class="bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700 transition-colors duration-200 font-medium">
                        Matières
                    </a>
                    <a href="logout.php" class="bg-red-600 text-white px-4 py-2 rounded-lg hover:bg-red-700 transition-colors duration-200 font-medium">
                        Déconnexion
                    </a>
                <?php else: ?>
                    <!-- Guest user menu -->
                    <a href="simple-subjects.php" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition-colors duration-200 font-medium">
                        Matières
                    </a>
                    <a href="login.php" class="bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700 transition-colors duration-200 font-medium">
                        Se Connecter
                    </a>
                    <a href="register.php" class="bg-purple-600 text-white px-4 py-2 rounded-lg hover:bg-purple-700 transition-colors duration-200 font-medium">
                        Créer un Compte
                    </a>
                <?php endif; ?>
            </nav>
        </div>
    </div>
</header>

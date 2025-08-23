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
                    <a href="user-dashboard.php" class="text-xl sm:text-2xl font-bold text-gray-900 hover:text-blue-600 transition-colors duration-200">Askiaverse</a>
                <?php else: ?>
                    <a href="index.php" class="text-xl sm:text-2xl font-bold text-gray-900 hover:text-blue-600 transition-colors duration-200">Askiaverse</a>
                <?php endif; ?>
            </div>
            
            <!-- Desktop Navigation (Hidden on mobile) -->
            <nav class="hidden md:flex space-x-3">
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
            
            <!-- Mobile Menu Button (Visible only on mobile) -->
            <div class="md:hidden">
                <label for="mobile-menu-toggle" class="cursor-pointer text-gray-600 hover:text-gray-900 focus:outline-none focus:text-gray-900 p-2">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                    </svg>
                </label>
            </div>
        </div>
        
        <!-- Mobile Menu (Hidden by default, shown when checkbox is checked) -->
        <input type="checkbox" id="mobile-menu-toggle" class="hidden peer">
        <div class="hidden peer-checked:block md:hidden pb-4 border-t border-gray-200">
            <div class="pt-4 space-y-2">
                <?php if (isset($_SESSION['user_id'])): ?>
                    <!-- Logged in user mobile menu -->
                    <a href="user-dashboard.php" class="block w-full bg-blue-600 text-white px-4 py-3 rounded-lg hover:bg-blue-700 transition-colors duration-200 font-medium text-center">
                        Tableau de Bord
                    </a>
                    <a href="simple-subjects.php" class="block w-full bg-green-600 text-white px-4 py-3 rounded-lg hover:bg-green-700 transition-colors duration-200 font-medium text-center">
                        Matières
                    </a>
                    <a href="logout.php" class="block w-full bg-red-600 text-white px-4 py-3 rounded-lg hover:bg-red-700 transition-colors duration-200 font-medium text-center">
                        Déconnexion
                    </a>
                <?php else: ?>
                    <!-- Guest user mobile menu -->
                    <a href="simple-subjects.php" class="block w-full bg-blue-600 text-white px-4 py-3 rounded-lg hover:bg-green-700 transition-colors duration-200 font-medium text-center">
                        Matières
                    </a>
                    <a href="login.php" class="block w-full bg-green-600 text-white px-4 py-3 rounded-lg hover:bg-green-700 transition-colors duration-200 font-medium text-center">
                        Se Connecter
                    </a>
                    <a href="register.php" class="block w-full bg-purple-600 text-white px-4 py-3 rounded-lg hover:bg-purple-700 transition-colors duration-200 font-medium text-center">
                        Créer un Compte
                    </a>
                <?php endif; ?>
            </div>
        </div>
    </div>
</header>

<style>
/* Custom CSS for mobile menu toggle */
#mobile-menu-toggle:checked ~ div {
    display: block;
}

/* Ensure the checkbox is properly hidden but accessible */
#mobile-menu-toggle {
    position: absolute;
    opacity: 0;
    pointer-events: none;
}

/* Add smooth transition for mobile menu */
.peer-checked\:block {
    animation: slideDown 0.3s ease-out;
}

@keyframes slideDown {
    from {
        opacity: 0;
        transform: translateY(-10px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}
</style>

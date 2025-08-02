<?php
// public/index.php - Front Controller

// Get the requested page from URL
$page = $_GET['page'] ?? 'index';

// Define allowed pages and their corresponding view files
$allowed_pages = [
    'index' => 'resources/views/index.php',
    'login' => 'resources/views/auth/login.php',
    'register' => 'resources/views/auth/register.php',
    'dashboard' => 'resources/views/dashboard.php',
    'quiz' => 'resources/views/quiz.php',
    'competition' => 'resources/views/competition.php',
    'community' => 'resources/views/community.php',
    'components' => 'resources/views/components.php'
];

// Check if the requested page exists
if (!isset($allowed_pages[$page])) {
    $page = 'index'; // Default to index if page not found
}

// Set the current page for navigation highlighting
$current_page = $page;

// Determine the view file to include
$view_file = null;
if (isset($allowed_pages[$current_page])) {
    $view_file = realpath(__DIR__ . '/../' . $allowed_pages[$current_page]);
}

// Include the layout which will handle the page content
require_once __DIR__ . '/../resources/views/layouts/app.php';
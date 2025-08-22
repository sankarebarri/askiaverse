<?php
// Set session directory to a writable location
ini_set('session.save_path', '/tmp');
session_start();

// Destroy all session data
session_destroy();

// Redirect to login page
    header('Location: login.php');
exit;
?>

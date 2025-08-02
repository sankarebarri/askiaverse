<?php
// Simple test to verify CSS loading
require_once __DIR__ . '/../src/Shared/Helpers.php';
?>
<!DOCTYPE html>
<html>
<head>
    <title>CSS Test</title>
    <?= vite_css_tag('resources/css/app.css') ?>
</head>
<body>
    <h1>CSS Test Page</h1>
    <p>If you see styled text, the CSS is loading correctly!</p>
    <p>Current time: <?= date('Y-m-d H:i:s') ?></p>
</body>
</html> 
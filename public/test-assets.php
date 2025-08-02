<?php
// Test asset paths and MIME types
require_once __DIR__ . '/../src/Shared/Helpers.php';
?>
<!DOCTYPE html>
<html>
<head>
    <title>Asset Test</title>
</head>
<body>
    <h1>Asset Path Test</h1>
    
    <h2>Current Configuration:</h2>
    <ul>
        <li><strong>ASSET_PREFIX:</strong> <?= env('ASSET_PREFIX', 'NOT SET') ?></li>
        <li><strong>Generated CSS Path:</strong> <?= \Shared\Config::asset('app.css') ?></li>
    </ul>
    
    <h2>Direct File Tests:</h2>
    <ul>
        <li><a href="/assets/app.css" target="_blank">Test /assets/app.css</a></li>
        <li><a href="/public/assets/app.css" target="_blank">Test /public/assets/app.css</a></li>
        <li><a href="assets/app.css" target="_blank">Test assets/app.css (relative)</a></li>
    </ul>
    
    <h2>CSS Loading Test:</h2>
    <div style="background: #f0f0f0; padding: 10px; margin: 10px 0;">
        <?= vite_css_tag('resources/css/app.css') ?>
    </div>
    
    <h2>Styled Text Test:</h2>
    <div style="margin: 20px 0;">
        <h3 class="text-2xl font-bold bg-gradient-to-r from-indigo-600 via-purple-600 to-pink-600 bg-clip-text text-transparent">
            If you see this styled text, CSS is working!
        </h3>
    </div>
    
    <h2>File Existence Check:</h2>
    <ul>
        <li><strong>public/assets/app.css:</strong> <?= file_exists(__DIR__ . '/assets/app.css') ? '✅ EXISTS' : '❌ NOT FOUND' ?></li>
        <li><strong>File size:</strong> <?= file_exists(__DIR__ . '/assets/app.css') ? filesize(__DIR__ . '/assets/app.css') . ' bytes' : 'N/A' ?></li>
    </ul>
</body>
</html> 
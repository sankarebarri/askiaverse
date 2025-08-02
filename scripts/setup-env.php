<?php

/**
 * Environment Setup Script
 * 
 * This script helps you set up environment files for different deployment scenarios.
 * Usage: php scripts/setup-env.php [environment]
 * 
 * Environments: local, production, staging
 */

// Check if environment is provided
$environment = $argv[1] ?? 'local';

// Validate environment
$validEnvironments = ['local', 'production', 'staging'];
if (!in_array($environment, $validEnvironments)) {
    echo "❌ Invalid environment. Use: local, production, or staging\n";
    exit(1);
}

echo "🚀 Setting up environment: {$environment}\n\n";

// Environment configurations
$configs = [
    'local' => [
        'APP_ENV' => 'local',
        'APP_DEBUG' => 'true',
        'APP_URL' => 'http://askiaverse.local',
        'ASSET_PREFIX' => '/assets/',
        'DB_HOST' => '127.0.0.1',
        'DB_DATABASE' => 'askiaverse',
        'DB_USERNAME' => 'root',
        'DB_PASSWORD' => '',
    ],
    'production' => [
        'APP_ENV' => 'production',
        'APP_DEBUG' => 'false',
        'APP_URL' => 'https://askiagames.com',
        'ASSET_PREFIX' => '/public/assets/',
        'DB_HOST' => 'localhost',
        'DB_DATABASE' => 'your_production_db',
        'DB_USERNAME' => 'your_db_user',
        'DB_PASSWORD' => 'your_secure_password',
    ],
    'staging' => [
        'APP_ENV' => 'staging',
        'APP_DEBUG' => 'true',
        'APP_URL' => 'https://staging.askiagames.com',
        'ASSET_PREFIX' => '/assets/',
        'DB_HOST' => 'localhost',
        'DB_DATABASE' => 'askiaverse_staging',
        'DB_USERNAME' => 'staging_user',
        'DB_PASSWORD' => 'staging_password',
    ]
];

// Generate APP_KEY
$appKey = 'base64:' . base64_encode(random_bytes(32));

// Build .env content
$envContent = "# ===================================================================\n";
$envContent .= "# ASKIAVERSE - {$environment} Environment\n";
$envContent .= "# Generated on: " . date('Y-m-d H:i:s') . "\n";
$envContent .= "# ===================================================================\n\n";

$envContent .= "# Application Environment\n";
$envContent .= "APP_ENV={$configs[$environment]['APP_ENV']}\n";
$envContent .= "APP_DEBUG={$configs[$environment]['APP_DEBUG']}\n";
$envContent .= "APP_URL={$configs[$environment]['APP_URL']}\n\n";

$envContent .= "# Asset Configuration\n";
$envContent .= "ASSET_PREFIX={$configs[$environment]['ASSET_PREFIX']}\n";
$envContent .= "ASSET_URL=\n\n";

$envContent .= "# Database Configuration\n";
$envContent .= "DB_HOST={$configs[$environment]['DB_HOST']}\n";
$envContent .= "DB_PORT=3306\n";
$envContent .= "DB_DATABASE={$configs[$environment]['DB_DATABASE']}\n";
$envContent .= "DB_USERNAME={$configs[$environment]['DB_USERNAME']}\n";
$envContent .= "DB_PASSWORD={$configs[$environment]['DB_PASSWORD']}\n\n";

$envContent .= "# Security\n";
$envContent .= "APP_KEY={$appKey}\n\n";

$envContent .= "# Mail Configuration\n";
$envContent .= "MAIL_MAILER=smtp\n";
$envContent .= "MAIL_HOST=mailpit\n";
$envContent .= "MAIL_PORT=1025\n";
$envContent .= "MAIL_USERNAME=null\n";
$envContent .= "MAIL_PASSWORD=null\n";
$envContent .= "MAIL_FROM_ADDRESS=\"hello@example.com\"\n";

// Write .env file
$envFile = __DIR__ . '/../.env';
if (file_put_contents($envFile, $envContent)) {
    echo "✅ Created .env file for {$environment} environment\n";
    echo "📁 File location: {$envFile}\n\n";
    
    echo "⚠️  IMPORTANT: Please review and update the following:\n";
    echo "   - Database credentials\n";
    echo "   - Application URL\n";
    echo "   - Mail configuration\n\n";
    
    echo "🔧 Next steps:\n";
    echo "   1. Edit .env file with your actual values\n";
    echo "   2. Run: composer install\n";
    echo "   3. Run: npm install\n";
    echo "   4. Run: npm run build\n";
    
    if ($environment === 'production') {
        echo "\n🔒 Security reminders for production:\n";
        echo "   - Use strong database passwords\n";
        echo "   - Enable HTTPS\n";
        echo "   - Set proper file permissions\n";
        echo "   - Keep .env file secure\n";
    }
} else {
    echo "❌ Failed to create .env file\n";
    exit(1);
} 
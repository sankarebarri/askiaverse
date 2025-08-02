<?php

namespace App\Shared;

/**
 * Configuration helper class
 * Provides a clean interface to access application configuration
 */
class Config
{
    private static $config = null;

    /**
     * Load configuration files
     */
    private static function loadConfig()
    {
        if (self::$config === null) {
            self::$config = require __DIR__ . '/../../config/config.php';
        }
    }

    /**
     * Get a configuration value
     *
     * @param string $key Dot notation key (e.g., 'app.env', 'database.host')
     * @param mixed $default Default value if key doesn't exist
     * @return mixed
     */
    public static function get(string $key, $default = null)
    {
        self::loadConfig();
        
        $keys = explode('.', $key);
        $value = self::$config;
        
        foreach ($keys as $segment) {
            if (!isset($value[$segment])) {
                return $default;
            }
            $value = $value[$segment];
        }
        
        return $value;
    }

    /**
     * Get asset URL with proper prefix
     *
     * @param string $path Asset path
     * @return string Full asset URL
     */
    public static function asset(string $path): string
    {
        // Get asset prefix from environment variable
        $prefix = env('ASSET_PREFIX', '/assets/');
        return $prefix . ltrim($path, '/');
    }

    /**
     * Check if application is in debug mode
     *
     * @return bool
     */
    public static function isDebug(): bool
    {
        return self::get('debug', false);
    }

    /**
     * Check if application is in production mode
     *
     * @return bool
     */
    public static function isProduction(): bool
    {
        return self::get('env') === 'production';
    }
} 
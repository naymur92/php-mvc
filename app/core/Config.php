<?php

namespace App\Core;

class Config
{
    private static array $config = [];

    /**
     * Load env file into memory
     *
     * @param string $filePath
     * @return void
     */
    public static function loadEnv(string $filePath = BASE_PATH . '.env'): void
    {
        if (!file_exists($filePath)) {
            throw new \Exception("Environment file not found: $filePath");
        }

        $lines = file($filePath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        foreach ($lines as $line) {
            // Skip comments
            if (strpos(trim($line), '#') === 0) {
                continue;
            }

            [$key, $value] = explode('=', $line, 2);
            self::$config[trim($key)] = trim($value, '"');
        }
    }

    /**
     * Get a config value by key
     */
    public static function get(string $key, $default = null)
    {
        return self::$config[$key] ?? $default;
    }
}

<?php

namespace App\Core;

class Env
{
    private static array $envConfig = [];

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
            self::$envConfig[trim($key)] = trim($value, '"');
        }
    }

    /**
     * Get a env config value by key
     */
    public static function get(string $key, $default = null)
    {
        return self::$envConfig[$key] ?? $default;
    }
}

<?php

namespace App\Core;

use Exception;

class Config
{
    private static array $config = [];

    /**
     * Load configuration files from the config directory
     *
     * @param string $fileName
     * @return void
     */
    public static function loadConfigFile(string $fileName = 'config'): void
    {
        $file = BASE_PATH . "config/$fileName.php";

        if (!file_exists($file)) {
            throw new Exception("Config file $fileName not found!");
        }
        self::$config[$fileName] = require $file;
    }

    /**
     * Get a config value by filename and keys, separated by . (Ex. 'config.value.data')
     */
    public static function get(string $fileAndkeys)
    {
        $keys = explode('.', $fileAndkeys);

        if (count($keys) < 1) {
            throw new Exception("Invalid config parameter $fileAndkeys!");
        }

        $fileName = array_shift($keys); // first value is filename

        self::loadConfigFile($fileName);

        $data = self::$config[$fileName];
        foreach ($keys as $key) {
            if (!isset($key, $data)) {
                throw new Exception("Invalid config key $key!");
            }

            $data = $data[$key];
        }

        return $data;
    }
}

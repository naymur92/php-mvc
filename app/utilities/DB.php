<?php

namespace App\Utilities;

use App\Core\Config;
use PDO;

class DB
{
    private static $instance = null;
    private $pdo;

    private function __construct()
    {
        try {
            // Get database credentials
            $dbHost     = Config::get('DB_HOST', '127.0.0.1');
            $dbPort     = Config::get('DB_PORT', '3306');
            $dbName     = Config::get('DB_DATABASE', 'test');
            $dbUser     = Config::get('DB_USERNAME', 'root');
            $dbPassword = Config::get('DB_PASSWORD', '');

            $this->pdo = new PDO("mysql:host=$dbHost;port=$dbPort;dbname=$dbName", $dbUser, $dbPassword);

            // Set default PDO options
            $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
        } catch (\PDOException $e) {
            die("Database connection failed: " . $e->getMessage());
        }
    }

    /**
     * Instance of current class
     *
     * @return PDO
     */
    public static function getInstance(): PDO
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance->pdo;
    }

    /**
     * Database query method
     *
     * @param string $sql
     * @param array $params
     * @return \PDOStatement
     */
    public static function query(string $sql, array $params = []): \PDOStatement
    {
        $stmt = self::getInstance()->prepare($sql);
        $stmt->execute($params);
        return $stmt;
    }
}

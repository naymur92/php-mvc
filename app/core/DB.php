<?php

namespace App\Core;

use PDO;

class DB
{
    private static ?DB $instance = null;
    private PDO $pdo;

    private string $table;
    private array $conditions = [];
    private array $selectColumns = ['*'];
    private array $params = [];

    private bool $isGrouped = false;

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
     * @return self
     */
    public static function getInstance(): self
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
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
        $stmt = self::getInstance()->pdo->prepare($sql);
        $stmt->execute($params);
        return $stmt;
    }

    public function table(string $table): self
    {
        $this->table = $table;
        return $this;
    }

    public function where(string $field, string $operator, $value): self
    {
        $condition = "$field $operator ?";
        $this->addCondition($condition, 'AND', $value);
        return $this;
    }

    public function orWhere(string $field, string $operator, $value): self
    {
        $condition = "$field $operator ?";
        $this->addCondition($condition, 'OR', $value);
        return $this;
    }

    private function addCondition(string $condition, string $type, $value): void
    {
        if (!empty($this->conditions)) {
            $this->conditions[] = $type; // AND or OR
        }

        if ($this->isGrouped) {
            $this->conditions[] = '(' . $condition;
            $this->isGrouped = false; // Reset grouping
        } else {
            $this->conditions[] = $condition;
        }

        $this->params[] = $value;
    }

    public function when(bool $condition, callable $callback): self
    {
        if ($condition) {
            $this->startGroup();    // Open a new group
            $callback($this);
            $this->endGroup();      // Close the group after callback
        }
        return $this;
    }

    private function startGroup(): void
    {
        // $this->conditions[] = '('; // Add an opening parenthesis
        $this->isGrouped = true;
    }

    private function endGroup(): void
    {
        $this->conditions[] = ')'; // Add a closing parenthesis
    }

    public function whereIn(string $field, array $values): self
    {
        $placeholders = implode(', ', array_fill(0, count($values), '?'));
        $this->conditions[] = "$field IN ($placeholders)";
        $this->params = array_merge($this->params, $values);
        return $this;
    }

    public function whereBetween(string $field, $start, $end): self
    {
        $this->conditions[] = "$field BETWEEN ? AND ?";
        $this->params[] = $start;
        $this->params[] = $end;
        return $this;
    }

    public function insert(string $table, array $data): bool
    {
        $columns = implode(', ', array_keys($data));
        $placeholders = implode(', ', array_fill(0, count($data), '?'));
        $sql = "INSERT INTO $table ($columns) VALUES ($placeholders)";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute(array_values($data));
    }

    public function update(string $table, int $id, array $data, string $primaryKey = 'id'): bool
    {
        $columns = implode(', ', array_map(fn($col) => "$col = ?", array_keys($data)));
        $sql = "UPDATE $table SET $columns WHERE $primaryKey = ?";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([...array_values($data), $id]);
    }

    public function find(string $table, int $id, string $primaryKey = 'id'): ?array
    {
        $sql = "SELECT * FROM $table WHERE $primaryKey = ?";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$id]);
        return $stmt->fetch() ?: null;
    }

    public function getAll(string $table): array
    {
        $sql = "SELECT * FROM $table";
        $stmt = $this->pdo->query($sql);
        return $stmt->fetchAll();
    }

    public function delete(string $table, int $id, string $primaryKey = 'id'): bool
    {
        $sql = "DELETE FROM $table WHERE $primaryKey = ?";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([$id]);
    }

    public function select(array $columns): self
    {
        $this->selectColumns = $columns;
        return $this;
    }

    public function addSelect(array $columns): self
    {
        $this->selectColumns = array_merge($this->selectColumns, $columns);
        return $this;
    }

    public function get(): array
    {
        $columns = implode(', ', $this->selectColumns); // Prepare column list
        $sql = "SELECT {$columns} FROM {$this->table}";

        // Add conditions if any
        if (!empty($this->conditions)) {
            $sql .= ' WHERE ' . implode(' ', $this->conditions);
        }

        $stmt = self::query($sql, $this->params);

        $this->reset();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }


    private function reset(): void
    {
        $this->conditions = [];
    }

    public function toSql(): string
    {
        $columns = implode(', ', $this->selectColumns); // Prepare column list
        $sql = "SELECT {$columns} FROM {$this->table}";

        if (!empty($this->conditions)) {
            $sql .= ' WHERE ' . implode(' ', $this->conditions);
        }

        return $sql;
    }

    /**
     * Get SQL with params substituted for debugging purposes.
     *
     * @return string
     */
    public function getSqlWithParams(): string
    {
        // Clone the query so we don't modify the original
        $sql = $this->toSql();

        // Iterate over parameters and replace placeholders with actual values
        foreach ($this->params as $key => $value) {
            $sql = preg_replace('/\?/', $this->quote($value), $sql, 1);
        }

        return $sql;
    }

    private function quote($value)
    {
        return "'$value'";
    }
}

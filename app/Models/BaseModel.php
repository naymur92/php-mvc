<?php

namespace App\Models;

use App\Core\DB;
use App\Contracts\ModelInterface;

abstract class BaseModel implements ModelInterface
{
    protected $table;
    protected $primaryKey = 'id';
    protected array $fillable = [];

    // Set the table dynamically for chaining
    public function table(string $table = null): static
    {
        if ($table) {
            $this->table = $table;
        }
        return $this;
    }

    // Filter fillable fields
    protected function filterFillable(array $data): array
    {
        return array_filter(
            $data,
            fn($key) => in_array($key, $this->fillable),
            ARRAY_FILTER_USE_KEY
        );
    }

    public function insert(array $data): bool
    {
        $data = $this->filterFillable($data);
        $db = DB::getInstance();
        return $db->insert($this->table, $data);
    }

    public function update(int $id, array $data): bool
    {
        $data = $this->filterFillable($data);
        $db = DB::getInstance();
        return $db->update($this->table, $id, $data, $this->primaryKey);
    }

    public function find(int $id): ?array
    {
        $db = DB::getInstance();
        return $db->find($this->table, $id, $this->primaryKey);
    }

    public function getAll(): array
    {
        $db = DB::getInstance();
        return $db->getAll($this->table);
    }

    public function delete(int $id): bool
    {
        $db = DB::getInstance();
        return $db->delete($this->table, $id, $this->primaryKey);
    }

    public function get(): array
    {
        return DB::getInstance()
            ->table($this->table)
            ->get();
    }

    public function where(string $field, string $operator, $value): self
    {
        DB::getInstance()->table($this->table)->where($field, $operator, $value);
        return $this;
    }

    public function orWhere(string $field, string $operator, $value): self
    {
        DB::getInstance()->table($this->table)->orWhere($field, $operator, $value);
        return $this;
    }


    public function whereIn(string $field, array $values): self
    {
        DB::getInstance()->table($this->table)->whereIn($field, $values);
        return $this;
    }

    public function whereBetween(string $field, $start, $end): self
    {
        DB::getInstance()->table($this->table)->whereBetween($field, $start, $end);
        return $this;
    }
}

<?php

namespace App\Models;

use App\Core\DB;
use App\Contracts\ModelInterface;

abstract class BaseModel implements ModelInterface
{
    protected $table;
    protected $primaryKey = 'id';
    protected array $fillable = [];

    /**
     * Set the table dynamically for chaining
     *
     * @param string|null $table
     * @return static
     */
    public function table(string $table = null): static
    {
        if ($table) {
            $this->table = $table;
        }
        return $this;
    }

    /**
     * Filter fillable fields
     *
     * @param array $data
     * @return array
     */
    protected function filterFillable(array $data): array
    {
        return array_filter(
            $data,
            fn($key) => in_array($key, $this->fillable),
            ARRAY_FILTER_USE_KEY
        );
    }

    /**
     * Insert data to table
     *
     * @param array $data
     * @return boolean
     */
    public function insert(array $data): bool
    {
        $data = $this->filterFillable($data);
        $db = DB::getInstance();
        return $db->insert($this->table, $data);
    }

    /**
     * Update table data
     *
     * @param integer $id
     * @param array $data
     * @return boolean
     */
    public function update(int $id, array $data): bool
    {
        $data = $this->filterFillable($data);
        $db = DB::getInstance();
        return $db->update($this->table, $id, $data, $this->primaryKey);
    }

    /**
     * Find table data
     *
     * @param integer $id
     * @return array|null
     */
    public function find(int $id): ?array
    {
        $db = DB::getInstance();
        return $db->find($this->table, $id, $this->primaryKey);
    }

    /**
     * Delete data from table (model)
     *
     * @param integer $id
     * @return boolean
     */
    public function delete(int $id): bool
    {
        $db = DB::getInstance();
        return $db->delete($this->table, $id, $this->primaryKey);
    }

    /**
     * Get all data from table (model)
     *
     * @return array
     */
    public function getAll(): array
    {
        $db = DB::getInstance();
        return $db->getAll($this->table);
    }



    /**
     * Add 'where' condition to query builder
     *
     * @param string $field
     * @param string $operator
     * @param mixed $value
     * @return self
     */
    public function where(string $field, string $operator, $value): self
    {
        DB::getInstance()->table($this->table)->where($field, $operator, $value);
        return $this;
    }

    /**
     * Add 'or where' condition to query builder
     *
     * @param string $field
     * @param string $operator
     * @param mixed $value
     * @return self
     */
    public function orWhere(string $field, string $operator, $value): self
    {
        DB::getInstance()->table($this->table)->orWhere($field, $operator, $value);
        return $this;
    }


    /**
     * Add 'where in' condition to query builder
     *
     * @param string $field
     * @param array $values
     * @return self
     */
    public function whereIn(string $field, array $values): self
    {
        DB::getInstance()->table($this->table)->whereIn($field, $values);
        return $this;
    }

    /**
     * Add 'where between' condition to query builder
     *
     * @param string $field
     * @param [type] $start
     * @param [type] $end
     * @return self
     */
    public function whereBetween(string $field, $start, $end): self
    {
        DB::getInstance()->table($this->table)->whereBetween($field, $start, $end);
        return $this;
    }

    /**
     * Get all data from table using query builder
     *
     * @return array
     */
    public function get(): array
    {
        return DB::getInstance()
            ->table($this->table)
            ->get();
    }
}

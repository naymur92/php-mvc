<?php

namespace App\Contracts;

interface ModelInterface
{
    public function insert(array $data): bool;
    public function update(int $id, array $data): bool;
    public function find(int $id): ?array;
    public function getAll(): array;
    public function delete(int $id): bool;

    // methods for advanced query building
    public function table(string $table): self;
    public function where(string $field, string $operator, $value): self;
    public function orWhere(string $field, string $operator, $value): self;
    public function when(bool $condition, callable $callback): self;
    public function get(): array;
}

<?php

namespace App\Core\Database;

interface IDatabase
{
    public function fetchAll(string $string, array $values = []): array;

    public function fetchRow(string $string, array $values = []): array;

    public function rowCount(string $string, array $values = []): int;

    public function delete(string $table, string $where, array $data): bool;

    public function getWhere(string $table, string $where, array $data): array;

    public function selectAll(string $table): array;

    public function query(string $sql, array $values = [], string $mode = null);

    public function save(string $table, array $fieldsAndValues): bool;

    public function update(string $table, array $fieldsAndValues, string $where): bool;
}

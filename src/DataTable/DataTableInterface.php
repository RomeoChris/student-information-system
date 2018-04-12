<?php

namespace App\DataTable;


interface DataTableInterface
{
    public function getData(): array;
    public function setTable(string $table): void;
    public function setColumns(array $columns = []): void;
    public function setPrimaryKey(string $key = 'id'): void;
}

<?php

namespace App\Core\Request;


interface IRequestData
{
    public function get($key): ?string;
    public function getAll(): array;
    public function getSanitized($key): ?string;
    public function count(): int;
    public function has($key): bool;
    public function isEmpty(): bool;
    public function set($key, $value): void;
    public function unset($key): void;
}

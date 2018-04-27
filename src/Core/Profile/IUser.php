<?php

namespace App\Core\Profile;


interface IUser
{
    public function create(string $table, array $fieldsAndValues): bool;
    public function logout(): bool;
    public function login(string $username, string $password): bool;
    public function find($username): bool;
    public function getUserData(): array;
}

<?php

namespace App\Core\Profile;


interface IProfile
{
    public function getUsername(): string;
    public function getFullName(): string;
    public function getPassword(): string;
    public function getEmail(): string;
    public function getRole(): string;
    public function getDateJoined(): string;
    public function getLastLogin(): string;
    public function getAll(): IRequestData;
    public function getPhoneNumber(): string;
}

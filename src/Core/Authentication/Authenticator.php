<?php

namespace App\Core\Authentication;


use App\Core\Routing\Redirect;
use App\Core\Session\Session;

class Authenticator
{
    private $role;
    private $loggedIn;

    public function __construct(Session $session)
    {
        $this->role = $session->get('role');
        $this->loggedIn = $session->has('login');
    }

    public function requireAdmin(): void
    {
        $this->requireLoggedIn();
        if (!$this->role === 'admin' || !$this->role === 'lecturer')
        {
            die('<h2 style="text-align: center; color: red">Not Authorized</h2>');
        }
    }

    public function requireLoggedIn(): void
    {
        if (!$this->isLoggedIn()) {
            Redirect::to();
        }
    }

    public function isLoggedIn(): bool
    {
        return $this->loggedIn;
    }

    public function isAdmin(): bool
    {
        return ($this->role === 'admin') || ($this->role === 'lecturer');
    }

    public function isStudent(): bool
    {
        return ($this->role === 'student');
    }
}

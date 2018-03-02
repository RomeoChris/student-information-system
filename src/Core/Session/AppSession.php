<?php

namespace App\Core\Session;


class AppSession
{
    public function get(string $key)
    {
        return $_SESSION[$key] ?? '';
    }

    public function getInt(string $key) :int
    {
        return (int)$this->get($key) ?? 0;
    }

    public function all() :array
    {
        return $_SESSION ?? [];
    }

    public function has(string $key) :bool
    {
        return array_key_exists($key, $this->all());
    }

    public function set(string $key, $value) :void
    {
        $_SESSION[$key] = $value;
    }

    public function remove(string $key) :void
    {
        unset($_SESSION[$key]);
    }

    public function flash(string $sessionName) :string
    {
        if ($this->has($sessionName))
        {
            $session = $this->get($sessionName);
            $this->remove($sessionName);
            return $session;
        }
        return '';
    }

    public function destroy() :void
    {
        session_unset();
        session_destroy();
    }
}

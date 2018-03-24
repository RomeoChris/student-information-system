<?php

namespace App\Core\Authenticator;


use App\Models\Profile;
use App\Storages\IStorage;
use App\Core\Session\AppSession;
use App\Core\Collection\AppCollection;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class Authenticator extends Controller
{
    private $session;
    private $identifier;
    private $profileStorage;

    public function __construct(IStorage $profileStorage, AppSession $session)
    {
        $this->session = $session;
        $this->identifier = $session->getInt('identifier');
        $this->profileStorage = $profileStorage;
    }

    public function login(string $username = '', string $password = '') :bool
    {
        $data = $this->profileStorage->getWhere('username = ?', [$username]);
        $profile = $this->profileStorage->convertToModel(new AppCollection($data));

        if (password_verify($password, $profile->getPasswordHash()))
        {
            $this->session->set('role', $profile->getRole());
            $this->session->set('username', $profile->getUsername());
            $this->session->set('identifier', $profile->getIdentifier());
            return true;
        }
        return false;
    }

    public function getProfile() :Profile
    {
        return $this->profileStorage->getById($this->identifier);
    }

    public function isLoggedIn() :bool
    {
        return $this->session->has('identifier') && $this->session->has('username');
    }

    public function requireHeadAdmin() :void
    {
        if ($this->getProfile()->getRole() !=  'head')
            die($this->getAuthorityMessage());
    }

    public function requireAdmin() :void
    {
        if ($this->getRole() !=  'admin' || $this->getRole() !=  'head')
            die($this->getAuthorityMessage());
    }

    public function requireLecturer() :void
    {
        if ($this->getRole() !=  'lecturer'
            || $this->getRole() !=  'admin'
            || $this->getRole() !=  'head')
            die($this->getAuthorityMessage());
    }

    public function isHeadAdmin() :bool
    {
        return $this->getProfile()->getRole() == 'head';
    }

    public function isAdmin() :bool
    {
        return $this->getProfile()->getRole() ==  ('admin' || 'head');
    }

    public function isLecturer() :bool
    {
        return $this->getProfile()->getRole() == ('lecturer' || 'admin' || 'head');
    }

    private function getRole() :string
    {
        return $this->getProfile()->getRole();
    }

    private function getAuthorityMessage() :string
    {
        return '<h2 style="text-align: center; color: red">Not Authorized</h2>';
    }
}

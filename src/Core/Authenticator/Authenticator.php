<?php

namespace App\Core\Authenticator;


use App\Core\Session\AppSession;
use App\Entity\Profile;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class Authenticator
{
    private $session;
    private $identifier;
    private $repository;

    public function __construct(EntityManagerInterface $entityManager, SessionInterface $session)
    {
        $this->session = $session;
        $this->identifier = (int)$session->get('identifier') ?? 0;
        $this->repository = $entityManager->getRepository(Profile::class);
    }

    public function login(string $username = '', string $password = '') :bool
    {
        /* @var $profile Profile */
        $profile = $this->repository->findOneBy(['username' => $username]);

        if (!password_verify($password, $profile->getPasswordHash()))
            return false;

        $this->session->set('role', $profile->getRole());
        $this->session->set('username', $profile->getUsername());
        $this->session->set('identifier', $profile->getId());

        return true;
    }

    public function getProfile() :Profile
    {
        /* @var $profile Profile */
        $profile = $this->repository->find($this->identifier);
        return $profile;
    }

    public function isLoggedIn() :bool
    {
        return $this->session->has('identifier') && $this->session->has('username');
    }

    public function requireHeadAdmin() :void
    {
        if (!$this->isHeadAdmin())
            die($this->getAuthorityMessage());
    }

    public function requireAdmin() :void
    {
        if (!$this->isAdmin())
            die($this->getAuthorityMessage());
    }

    public function requireLecturer() :void
    {
        if (!$this->isLecturer())
            die($this->getAuthorityMessage());
    }

    public function isHeadAdmin() :bool
    {
        return $this->getRole() == 'head';
    }

    public function isAdmin() :bool
    {
        return $this->getRole() ==  'admin' || $this->getRole() == 'head';
    }

    public function isLecturer() :bool
    {
        return $this->getRole() == 'head'
            || $this->getRole() == 'admin'
            || $this->getRole() == 'lecturer';
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

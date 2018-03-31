<?php

namespace App\Controller;


use DateTime;
use Symfony\Component\HttpFoundation\Response;

class PageController extends AppController
{
	public function logout() :Response
    {
        $profile = $this->getProfile();

        if ($this->getEntityManager()->contains($profile))
        {
            $profile->setLastLogin(new DateTime());
            $this->getEntityManager()->persist($profile);
            $this->getEntityManager()->flush();
        }
        $this->getSession()->destroy();
        return $this->redirectToRoute('index');
    }

    public function dashboard() :Response
    {
        if (!$this->getAuthenticator()->isLoggedIn())
            return $this->redirectToRoute('index');

        return $this->renderTemplate('dashboard.html.twig', [
            'online' => 2,
            'pageTitle' => 'Dashboard',
            'complaints' => $this->getComplaints(),
            'announcements' => $this->getAnnouncements(),
            'numberOfAdmins' => $this->getUsers('admin'),
            'numberOfStudents' => $this->getUsers('student'),
            'numberOfLecturers' => $this->getUsers('lecturer')
        ]);
    }

    private function getComplaints() :array
    {
        $query = 'SELECT * FROM complaint ORDER BY id DESC LIMIT 3';
        return $this->getDatabase()->fetchAll($query);
    }

    private function getAnnouncements() :array
    {
        $query = 'SELECT * FROM announcement ORDER BY id DESC LIMIT 3';
        return $this->getDatabase()->fetchAll($query);
    }

    private function getUsers(string $role) :int
    {
        $query = 'SELECT * FROM profile WHERE role = ?';
        return $this->getDatabase()->rowCount($query, [$role]);
    }
}

<?php

namespace App\Pages;


use App\Controller\AppController;
use Symfony\Component\HttpFoundation\Response;

class Dashboard extends AppController
{
    public function index() :Response
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

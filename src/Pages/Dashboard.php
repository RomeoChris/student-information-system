<?php

namespace App\Pages;


use App\Controller\AppController;

class Dashboard extends AppController
{
    private $database;
    private $authenticator;
    private $storageManager;

    public function __construct()
    {
        $this->database = $this->getDatabase();
        $this->authenticator = $this->getAuthenticator();
        $this->storageManager = $this->getStorageManager();
    }

    public function index()
    {
        $this->authenticator->requireLoggedIn();
        return $this->renderTemplate('dashboard.html.twig', [
            'online' => 2,
            'pageTitle' => 'Dashboard',
            'complaints' => $this->getComplaints(),
            'announcements' => $this->getAnnouncements(),
            'numberOfAdmins' => $this->getAdmins(),
            'numberOfStudents' => $this->getStudents(),
            'numberOfLecturers' => $this->getLecturers()
        ]);
    }

    private function getComplaints() :array
    {
        $query = 'SELECT * FROM complaints ORDER BY id DESC LIMIT 3';
        return $this->database->fetchAll($query);
    }

    private function getAnnouncements() :array
    {
        $query = 'SELECT * FROM announcements ORDER BY id DESC LIMIT 3';
        return $this->database->fetchAll($query);
    }

    private function getLecturers() :int
    {
        return $this->database->rowCount('SELECT * FROM lecturers');
    }

    private function getAdmins() :int
    {
        return $this->database->rowCount('SELECT * FROM admins');
    }

    private function getStudents() :int
    {
        return $this->database->rowCount('SELECT * FROM students');
    }
}

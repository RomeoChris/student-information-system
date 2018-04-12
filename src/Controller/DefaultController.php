<?php

namespace App\Controller;


use App\Entity\User;
use App\Repository\AnnouncementRepository;
use App\Repository\ComplaintRepository;
use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class DefaultController extends AbstractController
{
    protected static $ds = DIRECTORY_SEPARATOR;

    protected function getRootDir() :string
    {
        return __DIR__ . self::$ds . '..' . self::$ds . '..' . self::$ds;
    }

    protected function getProfile() :User
    {
        /* @var $profile User */
        $profile = $this->getUser();
        return $profile;
    }
 
    public function dashboard(
        UserRepository $userRepository,
        ComplaintRepository $complaintRepository,
        AnnouncementRepository $announcementRepository) :Response
    {
        return $this->render('dashboard.html.twig', [
            'online' => 2,
            'pageTitle' => 'Dashboard',
            'complaints' => $complaintRepository->getLatestComplaints(3),
            'announcements' => $announcementRepository->getLatestAnnouncements(3),
            'numberOfAdmins' => count($userRepository->getUsersWithRole('ROLE_ADMIN')),
            'numberOfStudents' => count($userRepository->getUsersWithRole('ROLE_USER')),
            'numberOfLecturers' => count($userRepository->getUsersWithRole('ROLE_LECTURER')),
        ]);
    }
    
    public function timetablesDownload() :Response
    {
        return $this->render('downloads/timetables.html.twig', [
            'pageTitle' => 'Timetables downloads'
        ]);
    }
    
    public function notesDownload() :Response
    {
        return $this->render('downloads/notes.html.twig', [
            'pageTitle' => 'Notes downloads'
        ]);
    }
}

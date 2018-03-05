<?php

namespace App\Controller;


use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PageController extends AppController
{
	public function logout() :Response
    {
        $profile = $this->getProfile();
        if ($profile->isSaved())
        {
            $profile->setLastLogin(date('Y-m-d h:i:sa'));
            $this->getStorageManager()->getProfileStorage()->save($profile);
        }
        $this->getSession()->destroy();
        return $this->redirectToRoute('index');
    }

    /**
     * @Route("/profile/", name="profile")
     */
    public function profile() {}

    /**
     * @Route("/students/", name="students")
     */
    public function students() {}

    /**
     * @Route("/students/new/", name="newStudent")
     */
    public function newStudent() {}

    /**
     * @Route("/notes/", name="notes")
     */
    public function notes() {}

    /**
     * @Route("/announcements/", name="announcements")
     */
    public function announcements() {}

    /**
     * @Route("/announcements/new/", name="newAnnouncement")
     */
    public function newAnnouncement() {}

    /**
     * @Route("/complaints/", name="complaints")
     */
    public function complaints() {}

    /**
     * @Route("/complaints/new/", name="newComplaint")
     */
    public function newComplaint() {}

    /**
     * @Route("/lecturers/", name="lecturers")
     */
    public function lecturers() {}

    /**
     * @Route("/admins/", name="admins")
     */
    public function admins() {}

    /**
     * @Route("/courses/", name="courses")
     */
    public function courses() {}

    /**
     * @Route("/courses/new/", name="newCourse")
     */
    public function newCourse() {}
}

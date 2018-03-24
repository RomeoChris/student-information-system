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
     * @Route("/lecturers/", name="lecturers")
     */
    public function lecturers() {}

    /**
     * @Route("/admins/", name="admins")
     */
    public function admins() {}
}

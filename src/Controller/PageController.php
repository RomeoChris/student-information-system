<?php

namespace App\Controller;


use DateTime;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

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

    /**
     * @Route("/profile/", name="profile")
     */
    public function profile() {}
}

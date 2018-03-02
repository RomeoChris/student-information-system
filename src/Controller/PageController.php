<?php

namespace App\Controller;


class PageController extends AppController
{
	public function logout()
    {
        $profile = $this->getProfile();
        if ($profile->isSaved())
        {
            $profile->setLastLogin(date('Y-m-d h:i:sa'));
            $this->getStorageManager()->getProfileStorage()->save($profile);
        }
        $this->getSession()->destroy();
        $this->redirectToRoute('index');
    }
}

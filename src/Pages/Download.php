<?php

namespace App\Pages;


use App\Controller\AppController;
use Symfony\Component\HttpFoundation\Response;

class Download extends AppController
{
    public function timetables() :Response
    {
        if (!$this->getAuthenticator()->isLoggedIn())
            return $this->redirectToRoute('index');

        return $this->renderTemplate('downloads/timetables.html.twig', [
            'pageTitle' => 'Timetables downloads'
        ]);
    }

    public function notes() :Response
    {
        if (!$this->getAuthenticator()->isLoggedIn())
            return $this->redirectToRoute('index');

        return $this->renderTemplate('downloads/notes.html.twig', [
            'pageTitle' => 'Notes downloads'
        ]);
    }
}

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

        return $this->renderTemplate('download/timetables.html.twig', [
            'pageTitle' => 'Timetables download'
        ]);
    }
}

<?php

namespace App\Pages;


use App\Core\App\IApp;
use App\Core\Controller;
use App\Models\DataModel;

class Timetables extends Controller
{
    private $app;
    private $data;

    public function __construct(IApp $app)
    {
        $this->app = $app;
        $this->data = new DataModel($this->app);
    }

    /**
     * @throws \Dwoo\Exception
     */
    public function index()
    {
        $this->app->getAuthenticator()->requireLoggedIn();
        $this->renderTemplate('timetables.tpl', array_merge($this->app->getDefinitions(),
            [
                'pageTitle' => 'Timetables download'
            ]
        ));
    }
}

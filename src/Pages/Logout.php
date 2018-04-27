<?php

namespace App\Pages;


use App\Core\App\IApp;
use App\Core\Routing\Redirect;

class Logout
{
    private $app;

    public function __construct(IApp $app)
    {
        $this->app = $app;
    }

    public function index()
    {
        session_destroy();
        Redirect::to('/login/');
    }
}

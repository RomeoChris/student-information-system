<?php

namespace App\Pages;


class Classmates
{
    public function index()
    {
        require_once View::renderTemplate('classmates');
    }
}

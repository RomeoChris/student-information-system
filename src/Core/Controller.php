<?php

namespace App\Core;


use App\Core\App\AppInitializer;
use App\Core\App\IApp;
use Dwoo;

class Controller
{

    /**
     * @param string $template
     * @param array $data
     * @return string|void
     * @throws \Dwoo\Exception
     */
    protected function renderTemplate(string $template, array $data = [])
    {
        $templateLoader = new Dwoo\Core();
        $templateLoader->setTemplateDir($this->getApp()->getTemplatesPath());
        echo $templateLoader->get($template, $data);
    }

    protected function getApp(): IApp
    {
        return AppInitializer::getApp();
    }
}

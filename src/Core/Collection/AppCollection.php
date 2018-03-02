<?php

namespace App\Core\Collection;


use App\Core\Sanitize\Sanitize;
use Symfony\Component\HttpFoundation\ParameterBag;

class AppCollection extends ParameterBag
{
    public function getString(string $key) :string
    {
        return (string)$this->get($key) ?? '';
    }

    public function getSanitized(string $key) :string 
    {
        return Sanitize::input($this->getString($key));
    }
}

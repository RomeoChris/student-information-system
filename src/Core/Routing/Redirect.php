<?php

namespace App\Core\Routing;


class Redirect
{
    public static function to(string $location = '/'): void
    {
        header("location: $location");
        exit();
    }
}

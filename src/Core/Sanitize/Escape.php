<?php

namespace App\Core\Sanitize;


class Escape
{
    public static function input($data)
    {
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        return $data;
    }
}

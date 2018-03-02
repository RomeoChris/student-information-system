<?php

namespace App\Core\Configuration;


use Exception;

class Configuration
{
    private $data;
    private $path;

    public function __construct(string $path = '')
    {
        if (!empty($path))
            $this->path = $path . 'data.ini';
        else
            $this->path = __DIR__ . DIRECTORY_SEPARATOR . 'data.ini';

        try
        {
            $this->data = parse_ini_file($this->path, true);
        }
        catch (Exception $e)
        {
            die('An attempt to load the data file failed: ' . $e->getMessage());
        }
    }

    public function getData() :array { return $this->data; }
}

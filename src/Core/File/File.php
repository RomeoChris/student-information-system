<?php

namespace App\Core\File;


use App\Core\Collection\AppCollection;

class File extends AppCollection
{
    private $fileName;

    public function getName() :string
    {
        return $this->getString('name');
    }

    public function getTempName() :string
    {
        return $this->getString('tmp_name');
    }

    public function getSize() :string
    {
        return $this->getString('size');
    }

    public function getError() :int
    {
        return $this->getInt('error');
    }

    public function upload(string $rootPath = '') :bool
    {
        $fullPath = $rootPath . $this->fileName;
        return move_uploaded_file($this->getTempName(), $fullPath);
    }

    public function setFileName() :void
    {
        $this->fileName = uniqid() . '-' . $this->getName();
    }

    public function getFileName() :string
    {
        return $this->fileName;
    }
}

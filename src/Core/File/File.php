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
        return move_uploaded_file($this->getTempName(), $rootPath);
    }

    public function getRootPath(string $rootPath = '') :string
    {
        return $rootPath . $this->fileName;
    }

    public function getDownloadPath(string $webPath = '') :string
    {
        return $webPath . $this->fileName;
    }

    public function setFileName() :void
    {
        $this->fileName = uniqid() . '-' . $this->getName();
    }

    public function getFileName() :string
    {
        return $this->fileName;
    }

    public static function uploadErrors() :array
    {
        return	[
            UPLOAD_ERR_OK => 'Your file was uploaded successfuly',
            UPLOAD_ERR_INI_SIZE => 'The uploaded file exceeds the upload max filesize.',
            UPLOAD_ERR_FORM_SIZE => 'The uploaded file exceeds the form upload max filesize.',
            UPLOAD_ERR_PARTIAL => 'The uploaded file was only partially uploaded. Try re-uploading the file again please.',
            UPLOAD_ERR_NO_FILE => 'No file was uploaded.',
            UPLOAD_ERR_NO_TMP_DIR => 'Upload Internal Server Error. Try again later.',
            UPLOAD_ERR_CANT_WRITE => 'Upload Internal Server Error. Try again later.',
            UPLOAD_ERR_EXTENSION => 'Upload Internal Server Error. Try again later.'
        ];
    }
}

<?php

namespace App\Models;


use App\Core\Collection\AppCollection;

class TimeTable extends Model
{
    protected $id;
    private $author;
    private $webPath;
    private $rootPath;
    private $description;
    private $dateCreated;
    private $dateUpdated;

    public function __construct(
        int $id = 0,
        string $author = '',
        string $webPath = '',
        string $rootPath = '',
        string $description = '',
        string $dateCreated = '',
        string $dateUpdated = ''
    )
    {
        $this->id = $id;
        $this->author = $author;
        $this->webPath = $webPath;
        $this->rootPath = $rootPath;
        $this->description = $description;
        $this->dateCreated = $dateCreated;
        $this->dateUpdated = $dateUpdated;
    }

    public static function empty() :self { return new self; }
    public function getAuthor() :string { return $this->author; }
    public function getWebPath() :string { return $this->webPath; }
    public function getRootPath() :string { return $this->rootPath; }
    public function getDescription() :string { return $this->description; }
    public function getDateCreated() :string { return $this->dateCreated; }
    public function getDateUpdated() :string { return $this->dateUpdated; }

    public function setAuthor(string $author = '') :void
    {
        $this->author = $author;
    }

    public function setWebPath(string $webPath = '') :void
    {
        $this->webPath = $webPath;
    }

    public function setRootPath(string $rootPath = '') :void
    {
        $this->rootPath = $rootPath;
    }

    public function setDescription(string $description = '') :void
    {
        $this->description = $description;
    }

    public function setDateUpdated(string $dateUpdated = '') :void
    {
        $this->dateUpdated = $dateUpdated;
    }

    public function saveAsData(): array
    {
        return [
            'description' => $this->description,
            'author' => $this->author,
            'root_path' => $this->rootPath,
            'web_path' => $this->webPath,
            'date_created' => $this->dateCreated,
            'date_updated' => $this->dateUpdated
        ];
    }

    public function convertToModel(AppCollection $data): IModel
    {
        return new self(
            $data->getInt('id'),
            $data->getString('author'),
            $data->getString('web_path'),
            $data->getString('root_path'),
            $data->getString('description'),
            $data->getString('date_created'),
            $data->getString('date_updated')
        );
    }
}

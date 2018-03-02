<?php

namespace App\Models;


use App\Core\Collection\AppCollection;

class Note extends Model
{
    protected $id;
    private $year;
    private $course;
    private $author;
    private $webPath;
    private $rootPath;
    private $semester;
    private $description;
    private $dateCreated;
    private $dateUpdated;

    public function __construct(
        int $id = 0,
        int $year = 0,
        string $course = '',
        string $author = '',
        string $webPath = '',
        string $rootPath = '',
        int $semester = 0,
        string $description = '',
        string $dateCreated = '',
        string $dateUpdated = ''
    )
    {
        $this->id = $id;
        $this->year = $year;
        $this->course = $course;
        $this->author = $author;
        $this->webPath = $webPath;
        $this->rootPath = $rootPath;
        $this->semester = $semester;
        $this->description = $description;
        $this->dateCreated = $dateCreated;
        $this->dateUpdated = $dateUpdated;
    }

    public static function empty() :self { return new self; }
    public function getYear() :string { return $this->year; }
    public function getCourse() :string { return $this->course; }
    public function getAuthor() :string { return $this->author; }
    public function getWebPath() :string { return $this->webPath; }
    public function getRootPath() :string { return $this->rootPath; }
    public function getSemester() :string { return $this->semester; }
    public function getDescription() :string { return $this->description; }
    public function getDateCreated() :string { return $this->dateCreated; }
    public function getDateUpdated() :string { return $this->dateUpdated; }

    public function setYear(int $year = 0) :void
    {
        $this->year = $year;
    }

    public function setCourse(string $course = '') :void
    {
        $this->course = $course;
    }

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

    public function setSemester(int $semester = 0) :void
    {
        $this->semester = $semester;
    }

    public function setDescription($description = '')
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
            'year' => $this->year,
            'course' => $this->course,
            'author' => $this->author,
            'root_path' => $this->rootPath,
            'semester' => $this->semester,
            'description' => $this->description,
            'web_path' => $this->webPath,
            'date_created' => $this->dateCreated,
            'date_updated' => $this->dateUpdated
        ];
    }

    public function convertToModel(AppCollection $data): IModel
    {
        return new self(
            $data->getInt('id'),
            $data->getInt('year'),
            $data->getString('course'),
            $data->getString('author'),
            $data->getString('web_path'),
            $data->getString('root_path'),
            $data->getInt('semester'),
            $data->getString('description'),
            $data->getString('date_created'),
            $data->getString('date_updated')
        );
    }
}

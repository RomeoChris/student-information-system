<?php

namespace App\Models;


use App\Core\Collection\AppCollection;

class Course extends Model
{
    protected $id;
    private $years;
    private $courseName;
    private $department;
    private $dateCreated;
    private $dateModified;

    public function __construct(
        int $id = 0,
        int $years = 0,
        string $courseName = '',
        string $department = '',
        string $dateCreated = '',
        string $dateModified = ''
    )
    {
        $this->id = $id;
        $this->years = $years;
        $this->courseName = $courseName;
        $this->department = $department;
        $this->dateCreated = $dateCreated;
        $this->dateModified = $dateModified;
    }

    public static function empty() :self { return new self; }
    public function getYears() :int { return $this->years; }
    public function getCourseName() :string { return $this->courseName; }
    public function getDepartment() :string { return $this->department; }
    public function getDateCreated() :string { return $this->dateCreated; }
    public function getDateModified() :string { return $this->dateModified; }

    public function setYears(int $years = 0) :void
    {
        $this->years = $years;
    }

    public function setCourseName(string $courseName = '') :void
    {
        $this->courseName = $courseName;
    }

    public function setDepartment(string $department = '') :void
    {
        $this->department = $department;
    }

    public function setDateModified(string $dateModified = '') :void
    {
        $this->dateModified = $dateModified;
    }

    public function saveAsData(): array
    {
        return [
            'name' => $this->courseName,
            'department' => $this->department,
            'years' => $this->years,
            'date_created' => $this->dateCreated,
            'date_modified' => $this->dateModified
        ];
    }

    public function convertToModel(AppCollection $data): IModel
    {
        return new self(
            $data->getInt('id'),
            $data->getInt('years'),
            $data->getString('name'),
            $data->getString('department'),
            $data->getString('date_created'),
            $data->getString('date_modified')
        );
    }
}

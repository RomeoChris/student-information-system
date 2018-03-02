<?php

namespace App\Models;


use App\Core\Collection\AppCollection;

class Announcement extends Model
{
    protected $id;
    private $title;
    private $author;
    private $message;
    private $dateCreated;
    private $dateUpdated;

    public function __construct(
        int $id = 0,
        string $title = '',
        string $author = '',
        string $message = '',
        string $dateCreated = '',
        string $dateUpdated = ''
    )
    {
        $this->id = $id;
        $this->title = $title;
        $this->author = $author;
        $this->message = $message;
        $this->dateCreated = $dateCreated;
        $this->dateUpdated = $dateUpdated;
    }

    public static function empty() :self { return new self; }
    public function getTitle() :string { return $this->title; }
    public function getAuthor() :string { return $this->author; }
    public function getMessage() :string { return $this->message; }
    public function getIdentifier() :int { return $this->id; }
    public function getDateCreated() :string { return $this->dateCreated; }
    public function getDateUpdated() :string { return $this->dateUpdated; }

    public function setTitle(string $title = '') :void
    {
        $this->title = $title;
    }

    public function setAuthor(string $author = '') :void
    {
        $this->author = $author;
    }

    public function setMessage(string $message = '') :void
    {
        $this->message = $message;
    }

    public function setDateUpdated(string $dateUpdated = '') :void
    {
        $this->dateUpdated = $dateUpdated;
    }

    public function saveAsData() :array
    {
        return [
            'title' => $this->title,
            'creator' => $this->author,
            'message' => $this->message,
            'date_added' => $this->dateCreated,
            'date_updated' => $this->dateUpdated
        ];
    }

    public function convertToModel(AppCollection $data) :IModel
    {
        return new self(
            $data->getInt('id'),
            $data->getString('title'),
            $data->getString('creator'),
            $data->getString('message'),
            $data->getString('date_added'),
            $data->getString('date_updated')
        );
    }
}

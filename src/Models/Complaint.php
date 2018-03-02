<?php

namespace App\Models;


use App\Core\Collection\AppCollection;

class Complaint extends Model
{
    protected $id;
    private $title;
    private $author;
    private $message;
    private $dateCreated;

    public function __construct(
        int $id = 0,
        string $title = '',
        string $author = '',
        string $message = '',
        string $dateCreated = ''
    )
    {
        $this->id = $id;
        $this->title = $title;
        $this->author = $author;
        $this->message = $message;
        $this->dateCreated = $dateCreated;
    }

    public static function empty() :self { return new self; }
    public function getTitle() :string { return $this->title; }
    public function getAuthor() :string { return $this->author; }
    public function getMessage() :string { return $this->message; }
    public function getDateCreated() :string { return $this->dateCreated; }

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

    public function saveAsData(): array
    {
        return [
            'title' => $this->title,
            'creator' => $this->author,
            'message' => $this->message,
            'date_added' => $this->dateCreated
        ];
    }

    public function convertToModel(AppCollection $data): IModel
    {
        return new self(
            $data->getInt('id'),
            $data->getString('title'),
            $data->getString('author'),
            $data->getString('message'),
            $data->getString('date_added')
        );
    }
}

<?php

namespace App\Models;


use App\Core\Collection\AppCollection;

abstract class Model implements IModel
{
    protected $id = 0;
    public function getIdentifier() :int { return $this->id; }
    public function setIdentifier(int $id = 0) :void { $this->id = $id; }
    public function isSaved() :bool { return $this->getIdentifier() > 0; }
    abstract public function saveAsData() :array;
    abstract public function convertToModel(AppCollection $data) :IModel;

    public function save() :array
    {
        $data = $this->saveAsData();
        $data['id'] = $this->getIdentifier();
        return $data;
    }
}

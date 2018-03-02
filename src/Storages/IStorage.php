<?php

namespace App\Storages;


use App\Models\IModel;
use App\Core\Collection\AppCollection;

interface IStorage
{
    function getById(int $id = 0) :IModel;
    function count(string $queryString) :int;
    function save(IModel $model) :bool;
    function delete(IModel $model) :bool;
    function getEmptyModel() :IModel;
    function getAll() :array;
    function countAll() :int;
    function getWhere(string $where, array $values = []) :array;
    function convertToModel(AppCollection $collection) :IModel;
}

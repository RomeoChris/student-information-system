<?php

namespace App\Models;


use App\Core\Collection\AppCollection;

interface IModel
{
    function getIdentifier() :int;
    function setIdentifier(int $id) :void;
    function isSaved() :bool;
    function save() :array;
    function convertToModel(AppCollection $collection) :IModel;
}

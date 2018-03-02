<?php

namespace App\Conversions;


interface IConversion
{
    function getTableName() :string;
    function getIdField() :string;
}

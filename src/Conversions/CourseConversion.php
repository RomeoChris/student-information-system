<?php

namespace App\Conversions;


class CourseConversion implements IConversion
{
    public function getIdField(): string { return 'id'; }
    public function getTableName(): string { return 'courses'; }
}

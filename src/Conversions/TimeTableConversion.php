<?php

namespace App\Conversions;


class TimeTableConversion implements IConversion
{
    public function getIdField(): string { return 'id'; }
    public function getTableName(): string { return 'timetables'; }
}

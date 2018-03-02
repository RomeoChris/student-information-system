<?php

namespace App\Conversions;


class ComplaintConversion implements IConversion
{
    public function getIdField(): string { return 'id'; }
    public function getTableName(): string { return 'complaints'; }
}

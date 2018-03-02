<?php

namespace App\Conversions;


class ProfileConversion implements IConversion
{
    public function getIdField() :string { return 'id'; }
    public function getTableName() :string { return 'profiles'; }
}

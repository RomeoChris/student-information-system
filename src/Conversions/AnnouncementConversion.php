<?php

namespace App\Conversions;


class AnnouncementConversion implements IConversion
{
    public function getIdField() :string { return 'id'; }
    public function getTableName() :string { return 'announcements'; }
}

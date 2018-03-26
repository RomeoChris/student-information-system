<?php

namespace App\Conversions;


class ProfileConversion implements IConversion
{
    public function getIdField() :string { return 'id'; }
    public function getTableName() :string { return 'profiles'; }

    public function getApiColumns() :array
    {
        return [
            ['db' => 'id', 'dt' => 0],
            ['db' => 'username', 'dt' => 1],
            ['db' => 'email', 'dt' => 2],
            ['db' => 'first_name', 'dt' => 3],
            ['db' => 'last_name', 'dt' => 4],
            ['db' => 'role', 'dt' => 5],
            [
                'db' => 'id',
                'dt' => 6,
                'formatter' => function($id)
                {
                    return "<a class='btn btn-info' href='/users/profile/$id/'>View Profile</a>";
                }
            ]
        ];
    }
}

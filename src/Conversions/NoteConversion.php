<?php

namespace App\Conversions;


class NoteConversion implements IConversion
{
    public function getIdField() :string { return 'id'; }
    public function getTableName() :string { return 'note'; }

    public function getApiColumns() :array
    {
        return [
            ['db' => 'title', 'dt' => 0],
            ['db' => 'author', 'dt' => 1],
            ['db' => 'course_id', 'dt' => 2],
            ['db' => 'year', 'dt' => 3],
            ['db' => 'semester', 'dt' => 4],
            ['db' => 'date_created', 'dt' => 5,
                'formatter' => function($d) { return date('jS M y', strtotime($d)); }
            ],
            ['db' => 'date_modified', 'dt' => 6,
                'formatter' => function($d) { return !empty($d) ? date('jS M y', strtotime($d)) : ''; }
            ],
            [
                'db' => 'file_name', 'dt' => 7,
                'formatter' => function($path) {
                    return "<a class='btn btn-primary' href='/downloads/$path'><i class='fa fa-download'></i> Download</a>";
                }
            ]
        ];
    }
}

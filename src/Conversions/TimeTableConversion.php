<?php

namespace App\Conversions;


class TimeTableConversion implements IConversion
{
    public function getIdField(): string { return 'id'; }
    public function getTableName(): string { return 'timetable'; }

    public function getApiColumns() :array
    {
        return [
            ['db' => 'title', 'dt' => 0],
            ['db' => 'author', 'dt' => 1],
            ['db' => 'date_created', 'dt' => 2,
                'formatter' => function($d) { return date('jS M y', strtotime($d)); }
            ],
            ['db' => 'date_modified', 'dt' => 3,
                'formatter' => function($d) { return !empty($d) ?date('jS M y', strtotime($d)) : ''; }
            ],
            ['db' => 'file_name', 'dt' => 4,
                'formatter' => function($path){
                    return "<a class='btn btn-primary' href='/downloads/$path'><i class='fa fa-download'></i> Download</a>";
                }
            ]
        ];
    }
}

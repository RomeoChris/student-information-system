<?php

namespace App\Conversions;


class TimeTableConversion implements IConversion
{
    public function getIdField(): string { return 'id'; }
    public function getTableName(): string { return 'timetables'; }

    public function getApiColumns() :array
    {
        return [
            ['db' => 'description', 'dt' => 0],
            ['db' => 'author', 'dt' => 1],
            ['db' => 'date_created', 'dt' => 2,
                'formatter' => function($d) { return date('jS M y', strtotime($d)); }
            ],
            ['db' => 'date_updated', 'dt' => 3,
                'formatter' => function($d) { return !empty($d) ?date('jS M y', strtotime($d)) : ''; }
            ],
            ['db' => 'web_path', 'dt' => 4,
                'formatter' => function($path){
                    return "<a class='btn btn-primary' href='$path'><i class='fa fa-download'></i> Download</a>";
                }
            ]
        ];
    }
}

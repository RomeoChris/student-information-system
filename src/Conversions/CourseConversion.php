<?php

namespace App\Conversions;


class CourseConversion implements IConversion
{
    public function getIdField(): string { return 'id'; }
    public function getTableName(): string { return 'course'; }

    public function getApiColumns() :array
    {
        return [
            ['db' => 'name', 'dt' => 0],
            ['db' => 'years', 'dt' => 1],
            ['db' => 'date_created', 'dt' => 2,
                'formatter' => function($d) { return date('jS M y', strtotime($d)); }
            ],
            ['db' => 'date_modified', 'dt' => 3,
                'formatter' => function($d) { return !empty($d) ? date('jS M y', strtotime($d)) : ''; }
            ],
            ['db' => 'id', 'dt' => 4,
                'formatter' => function($id) {
                    return "<a class='btn btn-info' href='/courses/view/$id/'>View course</a>";
                }
            ]
        ];
    }
}

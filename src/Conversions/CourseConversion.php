<?php

namespace App\Conversions;


class CourseConversion implements IConversion
{
    public function getIdField(): string { return 'id'; }
    public function getTableName(): string { return 'courses'; }

    public function getApiColumns() :array
    {
        return [
            ['db' => 'name', 'dt' => 0],
            ['db' => 'department', 'dt' => 1],
            ['db' => 'years', 'dt' => 2],
            ['db' => 'author', 'dt' => 3],
            ['db' => 'date_created', 'dt' => 4,
                'formatter' => function($d) { return date('jS M y', strtotime($d)); }
            ],
            ['db' => 'date_modified', 'dt' => 5,
                'formatter' => function($d) { return !empty($d) ? date('jS M y', strtotime($d)) : ''; }
            ],
            ['db' => 'id', 'dt' => 6,
                'formatter' => function($id) {
                    return "<a class='btn btn-info' href='/courses/view/$id/'>View course</a>";
                }
            ]
        ];
    }
}

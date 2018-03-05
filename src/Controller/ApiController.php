<?php

namespace App\Controller;


use App\Core\DataTable\DataTable;
use App\Conversions\NoteConversion;
use App\Conversions\TimeTableConversion;
use App\Conversions\AnnouncementConversion;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;

class ApiController extends AppController
{

    public function timetables() :Response
    {
        $dataTable = $this->dataTable();
        $conversion = new TimeTableConversion();
        $dataTable->setTable($conversion->getTableName());
        $dataTable->setColumns($conversion->getApiColumns());
        $dataTable->setPrimaryKey($conversion->getIdField());
        return new JsonResponse($dataTable->getData());
    }

    public function notes() :Response
    {
        $dataTable = $this->dataTable();
        $conversion = new NoteConversion();
        $dataTable->setTable($conversion->getTableName());
        $dataTable->setColumns($conversion->getApiColumns());
        $dataTable->setPrimaryKey($conversion->getIdField());
        return new JsonResponse($dataTable->getData());
    }

    public function announcements() :Response
    {
        $dataTable = $this->dataTable();
        $conversion = new AnnouncementConversion;
        $dataTable->setTable($conversion->getTableName());
        $dataTable->setColumns($conversion->getApiColumns());
        $dataTable->setPrimaryKey($conversion->getIdField());
        return new JsonResponse($dataTable->getData());
    }

    private function dataTable() :DataTable
    {
        return $this->getDataTable();
    }
}

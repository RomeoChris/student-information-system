<?php

namespace App\Controller;


use App\Conversions\ProfileConversion;
use App\Core\DataTable\DataTable;
use App\Conversions\NoteConversion;
use App\Conversions\CourseConversion;
use App\Conversions\TimeTableConversion;
use App\Conversions\ComplaintConversion;
use App\Conversions\AnnouncementConversion;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;

class ApiController extends DefaultController
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

    public function complaints() :Response
    {
        $dataTable = $this->dataTable();
        $conversion = new ComplaintConversion;
        $dataTable->setTable($conversion->getTableName());
        $dataTable->setColumns($conversion->getApiColumns());
        $dataTable->setPrimaryKey($conversion->getIdField());
        return new JsonResponse($dataTable->getData());
    }

    public function courses() :Response
    {
        $dataTable = $this->dataTable();
        $conversion = new CourseConversion;
        $dataTable->setTable($conversion->getTableName());
        $dataTable->setColumns($conversion->getApiColumns());
        $dataTable->setPrimaryKey($conversion->getIdField());
        return new JsonResponse($dataTable->getData());
    }

    public function users() :Response
    {
        $dataTable = $this->dataTable();
        $conversion = new ProfileConversion;
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

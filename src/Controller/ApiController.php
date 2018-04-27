<?php

namespace App\Controller;


use App\Conversions\ProfileConversion;
use App\Conversions\NoteConversion;
use App\Conversions\CourseConversion;
use App\Conversions\TimeTableConversion;
use App\Conversions\ComplaintConversion;
use App\Conversions\AnnouncementConversion;
use App\DataTable\DataTableInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;

class ApiController extends DefaultController
{
    public function timetables(DataTableInterface $dataTable) :Response
    {
        $conversion = new TimeTableConversion();
        $dataTable->setTable($conversion->getTableName());
        $dataTable->setColumns($conversion->getApiColumns());
        $dataTable->setPrimaryKey($conversion->getIdField());
        return new JsonResponse($dataTable->getData());
    }

    public function notes(DataTableInterface $dataTable) :Response
    {
        $conversion = new NoteConversion();
        $dataTable->setTable($conversion->getTableName());
        $dataTable->setColumns($conversion->getApiColumns());
        $dataTable->setPrimaryKey($conversion->getIdField());
        return new JsonResponse($dataTable->getData());
    }

    public function announcements(DataTableInterface $dataTable) :Response
    {
        $conversion = new AnnouncementConversion;
        $dataTable->setTable($conversion->getTableName());
        $dataTable->setColumns($conversion->getApiColumns());
        $dataTable->setPrimaryKey($conversion->getIdField());
        return new JsonResponse($dataTable->getData());
    }

    public function complaints(DataTableInterface $dataTable) :Response
    {
        $conversion = new ComplaintConversion;
        $dataTable->setTable($conversion->getTableName());
        $dataTable->setColumns($conversion->getApiColumns());
        $dataTable->setPrimaryKey($conversion->getIdField());
        return new JsonResponse($dataTable->getData());
    }

    public function courses(DataTableInterface $dataTable) :Response
    {
        $conversion = new CourseConversion;
        $dataTable->setTable($conversion->getTableName());
        $dataTable->setColumns($conversion->getApiColumns());
        $dataTable->setPrimaryKey($conversion->getIdField());
        return new JsonResponse($dataTable->getData());
    }

    public function users(DataTableInterface $dataTable) :Response
    {
        $conversion = new ProfileConversion;
        $dataTable->setTable($conversion->getTableName());
        $dataTable->setColumns($conversion->getApiColumns());
        $dataTable->setPrimaryKey($conversion->getIdField());
        return new JsonResponse($dataTable->getData());
    }
}

<?php

namespace App\Pages;


use Exception;
use App\Models\Note;
use App\Core\File\File;
use App\Models\TimeTable;
use App\Controller\AppController;
use Symfony\Component\HttpFoundation\Response;

class Uploads extends AppController
{
    private $post;
    private $webPath;
    private $rootPath;
    private $maxFileSize = 5000000;
    private $uploadErrors;
    private $storageManager;

    public function __construct()
    {
        $this->post = $this->getPost();
        $this->webPath = '/downloads/';
        $this->rootPath = $this->getRootPath();
        $this->uploadErrors = File::uploadErrors();
        $this->storageManager = $this->getStorageManager();
    }

    public function notes()
    {
        if (!$this->getAuthenticator()->isLoggedIn())
            return $this->redirectToRoute('index');

        $this->getAuthenticator()->requireLecturer();

        $errorList = [];

        if ($this->getRequest()->isMethod('post'))
        {
            $year = $this->post->getInt('year');
            $file = new File($_FILES['file']);
            $error = $this->uploadErrors[$file->getError()];
            $author = $this->getProfile()->getUsername();
            $semester = $this->post->getInt('semester');
            $courseName = $this->post->get('course');
            $description = $this->post->get('description');

            if (empty(($description || $courseName || $year || $semester)))
            {
                $errorList[] = 'All fields are required';
            }
            if ($file->getSize() <= $this->maxFileSize && $file->getError() == 0)
            {
                $file->setFileName();
                $filePath = $file->getRootPath($this->rootPath);
                $uploadFile = $file->upload($filePath);

                if (empty($errorList) && $uploadFile)
                {
                    $date = date('Y-m-d h:i:sa');
                    $webPath = $file->getDownloadPath($this->webPath);
                    $note = new Note(
                        0, $year, $courseName, $author, $webPath, $filePath, $semester, $description, $date);

                    if ($this->storageManager->getNoteStorage()->save($note))
                    {
                        $this->getSession()->set('successNote', 'Uploaded successfully');
                        return $this->redirectToRoute('uploadNotes');
                    }
                    else
                    {
                        $errorList[] = 'Internal server error. Please try again later';
                    }
                }
                else
                {
                    $errorList[] = 'Internal server error. Please try again later';
                }
            }
            else
            {
                $errorLis[] = $error;
            }
        }

        return $this->renderTemplate('uploads/notes.html.twig', [
            'errors' => $errorList,
            'courses' => $this->storageManager->getCourseStorage()->getAll(),
            'success' => $this->getSession()->flash('successNote'),
            'pageTitle' => 'Upload notes',
            'description' => $this->post->get('description')
        ]);
    }

    public function timetables() :Response
    {
        if (!$this->getAuthenticator()->isLoggedIn())
            return $this->redirectToRoute('index');

        $this->getAuthenticator()->requireLecturer();

        $errorList = [];

        if ($this->getRequest()->isMethod('post'))
        {
            $file = new File($_FILES['file']);
            $error = $this->uploadErrors[$file->getError()];
            $author = $this->getProfile()->getUsername();
            $description = $this->post->get('description');

            if (empty($description))
            {
                $errorList[] = 'All fields are required';
            }
            if ($file->getSize() <= $this->maxFileSize && $file->getError() == 0)
            {
                $file->setFileName();
                $rootPath = $file->getRootPath($this->rootPath);
                $uploadFile = $file->upload($rootPath);

                if (!count($errorList) && $uploadFile)
                {
                    $date = date('Y-m-d h:i:sa');
                    $webPath = $file->getDownloadPath($this->webPath);
                    $timeTable = new TimeTable(0, $author, $webPath, $rootPath, $description, $date);

                    if ($this->storageManager->getTimeTableStorage()->save($timeTable))
                    {
                        $this->getSession()->set('successTimeTable', 'Uploaded successfully');
                        return $this->redirectToRoute('uploadTimetables');
                    }
                    else
                    {
                        $errorList[] = 'Internal server error. Please try again later';
                    }
                }
                else
                {
                    $errorList[] = 'Internal server error. Please try again later';
                }
            }
            else
            {
                $errorList[] = $error;
            }
        }

        return $this->renderTemplate('uploads/timetables.html.twig', [
            'errors' => $errorList,
            'success' => $this->getSession()->flash('successTimeTable'),
            'pageTitle' => 'Upload timetable',
            'description' => $this->post->get('description')
        ]);
    }

    private function getRootPath() :string
    {
        $ds = DIRECTORY_SEPARATOR;
        $path = $this->getRootDir() . 'public' . $ds . 'downloads' . $ds;

        if (!file_exists($path))
        {
            try
            {
                mkdir($path, 0777, true);
            }
            catch (Exception $e)
            {
                die('An attempt to create downloads folder failed: ' . $e->getMessage());
            }
        }
        return $path;
    }
}

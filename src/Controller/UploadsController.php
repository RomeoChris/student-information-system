<?php

namespace App\Controller;


use App\Entity\Note;
use App\Entity\Timetable;
use DateTime;
use Exception;
use App\Core\File\File;
use Symfony\Component\HttpFoundation\Response;

class UploadsController extends DefaultController
{
    private $webPath = '/downloads/';
    private $maxFileSize = 5000000;

    public function notes()
    {
        if (!$this->getAuthenticator()->isLoggedIn())
            return $this->redirectToRoute('index');

        $this->getAuthenticator()->requireLecturer();

        $year = $this->getPost()->getInt('year');
        $title = $this->getPost()->get('title');
        $semester = $this->getPost()->getInt('semester');
        $courseId = $this->getPost()->get('courseId');

        $errorList = [];

        if ($this->getRequest()->isMethod('post'))
        {
            $file = new File($_FILES['file']);
            $error = File::uploadErrors()[$file->getError()];
            $author = $this->getProfile()->getUsername();

            if (empty(($title || $courseId || $year || $semester)))
                $errorList[] = 'All fields are required';

            if ($file->getSize() <= $this->maxFileSize && $file->getError() == 0)
            {
                $file->setFileName();
                $uploadFile = $file->upload($file->getRootPath($this->getRootPath()));

                if (empty($errorList) && $uploadFile)
                {
                    $note = new Note();
                    $note->setYear($year);
                    $note->setPath($file->getDownloadPath($this->webPath));
                    $note->setTitle($title);
                    $note->setAuthor($author);
                    $note->setCourseId($courseId);
                    $note->setSemester($semester);
                    $note->setDateCreated(new DateTime());

                    $this->entityManager->persist($note);
                    $this->entityManager->flush();

                    $this->getSession()->set('successNote', 'Uploaded successfully');
                    return $this->redirectToRoute('uploadNotes');
                }
                $errorList[] = 'Internal server error. Please try again later';
            }
            $errorLis[] = $error;
        }

        return $this->renderTemplate('uploads/notes.html.twig', [
            'errors' => $errorList,
            'title' => $title,
            'courses' => $this->getCourseRepository()->findAll(),
            'success' => $this->getSession()->flash('successNote'),
            'pageTitle' => 'Upload notes',
        ]);
    }

    public function timetables() :Response
    {
        if (!$this->getAuthenticator()->isLoggedIn())
            return $this->redirectToRoute('index');

        $this->getAuthenticator()->requireLecturer();

        $title = $this->getPost()->get('title');
        $errorList = [];

        if ($this->getRequest()->isMethod('post'))
        {
            $file = new File($_FILES['file']);
            $error = File::uploadErrors()[$file->getError()];
            $author = $this->getProfile()->getUsername();

            if (empty($title))
                $errorList[] = 'All fields are required';

            if ($file->getSize() <= $this->maxFileSize && $file->getError() == 0)
            {
                $file->setFileName();
                $uploadFile = $file->upload($file->getRootPath($this->getRootPath()));

                if (!count($errorList) && $uploadFile)
                {
                    $timetable = new Timetable();
                    $timetable->setPath($file->getDownloadPath($this->webPath));
                    $timetable->setTitle($title);
                    $timetable->setAuthor($author);
                    $timetable->setDateCreated(new DateTime());

                    $this->entityManager->persist($timetable);
                    $this->entityManager->flush();

                    $this->getSession()->set('successTimeTable', 'Uploaded successfully');
                    return $this->redirectToRoute('uploadTimetables');
                }
                $errorList[] = 'Internal server error. Please try again later';
            }
            $errorList[] = $error;
        }

        return $this->renderTemplate('uploads/timetables.html.twig', [
            'title' => $title,
            'errors' => $errorList,
            'success' => $this->getSession()->flash('successTimeTable'),
            'pageTitle' => 'Upload timetable',
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

<?php

namespace App\Controller;


use App\Core\File\File;
use App\Entity\Note;
use App\Entity\Timetable;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class UploadsController extends DefaultController
{
    private $webPath = '/downloads/';
    private $maxFileSize = 5000000;
 
    public function notes(Request $request)
    {
        $year = $request->request->getInt('year', 0);
        $title = $request->request->get('title', '');
        $semester = $request->request->getInt('semester', 0);
        $courseId = $request->request->getInt('courseId', 0);

        $errorList = [];

        if ($request->isMethod('post'))
        {
            $file = new File($_FILES['file']);
            $error = File::uploadErrors()[$file->getError()];
            $author = $this->getUser()->getUsername();

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

                    $this->addFlash('successNote', 'Uploaded successfully');
                    return $this->redirectToRoute('uploadNotes');
                }
                $errorList[] = 'Internal server error. Please try again later';
            }
            $errorLis[] = $error;
        }

        return $this->render('uploads/notes.html.twig', [
            'errors' => $errorList,
            'title' => $title,
            'courses' => $this->getCourseRepository()->findAll(),
            'pageTitle' => 'Upload notes',
        ]);
    }

    public function timetables(Request $request, EntityManagerInterface $entityManager) :Response
    {
        $title = $request->request->get('title');
        $errorList = [];

        if ($request->isMethod('post'))
        {
            $file = new File($_FILES['file']);
            $error = File::uploadErrors()[$file->getError()];
            $author = $this->getUser()->getUsername();

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

                    $entityManager->persist($timetable);
                    $entityManager->flush();

                    $this->addFlash('success', 'Uploaded successfully');
                    return $this->redirectToRoute('uploadTimetables');
                }
                $errorList[] = 'Internal server error. Please try again later';
            }
            $errorList[] = $error;
        }

        return $this->render('uploads/timetables.html.twig', [
            'title' => $title,
            'errors' => $errorList,
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

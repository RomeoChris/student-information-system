<?php

namespace App\Controller;


use App\Entity\Note;
use App\Entity\Timetable;
use App\Form\UploadNoteType;
use App\Form\UploadTimetableType;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class UploadsController extends DefaultController
{
    public function notes(Request $request, EntityManagerInterface $entityManager)
    {
        $note = new Note;
        $form = $this->createForm(UploadNoteType::class, $note);
        $form->handleRequest($request);
    
        if (!$form->isSubmitted() || !$form->isValid())
            return $this->render('uploads/notes.html.twig', [
                'form' => $form->createView(),
                'pageTitle' => 'Upload notes',
                'formHeader' => 'Upload new notes',
            ]);
        
        /** @var UploadedFile $file */
        $file = $form->get('file_name')->getData();
        $fileName = $this->generateUniqueFileName() . '.' . $file->guessExtension();

        $file->move($this->getRootPath(), $fileName);

        $note->setAuthor($this->getUser()->getUsername());
        $note->setFileName($fileName);
        $note->setDateCreated();

        $entityManager->persist($note);
        $entityManager->flush();

        $this->addFlash('success', 'Notes successfully uploaded');
        return $this->redirectToRoute('notes');
    }

    public function timetables(Request $request, EntityManagerInterface $entityManager) :Response
    {
        $timetable = new Timetable;
        $form = $this->createForm(UploadTimetableType::class, $timetable);
        $form->handleRequest($request);
    
        if (!$form->isSubmitted() || !$form->isValid())
            return $this->render('uploads/timetables.html.twig', [
                'form' => $form->createView(),
                'pageTitle' => 'Upload timetable',
                'formHeader' => 'Upload new timetable',
            ]);
        
        /** @var UploadedFile $file */
        $file = $form->get('file_name')->getData();
        $fileName = $this->generateUniqueFileName() . '.' . $file->guessExtension();
    
        $file->move($this->getRootPath(), $fileName);
    
        $timetable->setAuthor($this->getUser()->getUsername());
        $timetable->setFileName($fileName);
        $timetable->setDateCreated();
    
        $entityManager->persist($timetable);
        $entityManager->flush();
    
        $this->addFlash('success', 'Timetable successfully uploaded');
        return $this->redirectToRoute('timetables');
    }
    
    private function generateUniqueFileName() :string { return md5(uniqid()); }

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

<?php

namespace App\Controller;


use App\Entity\Complaint;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class ComplaintsController extends DefaultController
{
    public function index() :Response
    {
        return $this->render('complaints/index.html.twig', [
            'pageTitle' => 'All Complaints'
        ]);
    }

    public function new(Request $request, EntityManagerInterface $entityManager) :Response
    {
        $title = $request->request->get('title', '');
        $author = $this->getUser()->getUsername();
        $message = $request->request->get('message', '');
        $errorList = [];

        if ($request->isMethod('post'))
        {
            if (empty(($title || $message)))
                $errorList[] = 'All fields are required';

            if (empty($errorList))
            {
                $complaint = new Complaint();
                $complaint->setTitle($title);
                $complaint->setAuthor($author);
                $complaint->setMessage($message);
                $complaint->setDateCreated(new DateTime());

                $entityManager->persist($complaint);
                $entityManager->flush();

                $this->addFlash('success', 'Complaint has been sent to our admins. Thanks');
                return $this->redirectToRoute('newComplaint');
            }
        }

        return $this->render('complaints/new.html.twig', [
            'title' => $title,
            'errors' => $errorList,
            'message' => $message,
            'pageTitle' => 'Add new complaint',
        ]);
    }

    public function view(Complaint $complaint) :Response
    {
        return $this->render('complaints/view.html.twig', [
            'id' => $complaint->getId(),
            'title' => $complaint->getTitle(),
            'author' => $complaint->getAuthor(),
            'message' => $complaint->getMessage(),
            'pageTitle' => $complaint->getTitle()
        ]);
    }

    public function delete(Complaint $complaint, EntityManagerInterface $entityManager) :Response
    {
        $entityManager->remove($complaint);
        $entityManager->flush();

        $this->addFlash('success', 'Complaint deleted successfully');
        return $this->redirectToRoute('complaints');
    }
}

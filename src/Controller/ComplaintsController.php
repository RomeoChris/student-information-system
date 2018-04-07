<?php

namespace App\Controller;


use App\Entity\Complaint;
use App\Form\ComplaintType;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class ComplaintsController extends DefaultController
{
    public function index() :Response
    {
        $params = ['pageTitle' => 'All Complaints'];
        return $this->render('complaints/index.html.twig', $params);
    }

    public function new(Request $request, EntityManagerInterface $entityManager) :Response
    {
        $complaint = new Complaint;
        $form = $this->createForm(ComplaintType::class, $complaint);
        $form->handleRequest($request);
    
        $params = [
            'form' => $form->createView(),
            'pageTitle' => 'Add new complaint',
            'formHeader' => 'Add new complaint',
        ];
    
        if (!$form->isSubmitted() || !$form->isValid())
            return $this->render('complaints/new.html.twig', $params);
    
        $complaint->setAuthor($this->getUser()->getUsername());
        $complaint->setDateCreated();
    
        $entityManager->persist($complaint);
        $entityManager->flush();
    
        $this->addFlash('success', 'Complaint has been sent to our admins. Thanks');
        return $this->redirectToRoute('newComplaint');
    }

    public function view(Complaint $complaint) :Response
    {
        return $this->render('complaints/view.html.twig', [
            'id' => $complaint->getId(),
            'title' => $complaint->getTitle(),
            'message' => $complaint->getMessage(),
            'dateTime' => $complaint->getDateCreated(),
            'pageTitle' => $complaint->getTitle(),
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

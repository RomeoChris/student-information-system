<?php

namespace App\Controller;


use App\Entity\Announcement;
use App\Form\AnnouncementType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class AnnouncementsController extends DefaultController
{
	public function index() :Response
	{
	    $params = ['pageTitle' => 'All announcements'];
		return $this->render('announcements/index.html.twig', $params);
	}

	public function new(Request $request, EntityManagerInterface $entityManager) :Response
	{
        $announcement = new Announcement;
        $form = $this->createForm(AnnouncementType::class, $announcement);
        $form->handleRequest($request);
        
        $params = [
            'form' => $form->createView(),
            'pageTitle' => 'New Announcement',
            'formHeader' => 'New Announcement',
        ];
        
        if (!$form->isSubmitted() || !$form->isValid())
            return $this->render('announcements/new.html.twig', $params);
        
        $announcement->setAuthor($this->getUser()->getUsername());
        $announcement->setDateCreated();
        
        $entityManager->persist($announcement);
        $entityManager->flush();
        
        $this->addFlash('success', 'Announcement successfully posted');
        return $this->redirectToRoute('newAnnouncement');
	}

	public function view(Announcement $announcement) :Response
	{
        return $this->render('announcements/view.html.twig', [
            'id' => $announcement->getId(),
            'title' => $announcement->getTitle(),
            'author' => $announcement->getAuthor(),
            'message' => $announcement->getMessage(),
            'dateTime' => $announcement->getDateCreated(),
            'pageTitle' => $announcement->getTitle(),
        ]);
	}

	public function edit(
	    Announcement $announcement,
        Request $request,
        EntityManagerInterface $entityManager) :Response
	{
        $form = $this->createForm(AnnouncementType::class, $announcement);
        $form->handleRequest($request);
        
        $params = [
            'form' => $form->createView(),
            'pageTitle' => 'Update Announcement',
            'formHeader' => 'Update Announcement',
        ];
        
        if (!$form->isSubmitted() || !$form->isValid())
            return $this->render('announcements/edit.html.twig', $params);
        
        $announcement->setAuthor($this->getUser()->getUsername());
        $announcement->setDateModified();
        
        $entityManager->persist($announcement);
        $entityManager->flush();
        
        $this->addFlash('success', 'Announcement updated successfully');
        return $this->redirectToRoute('editAnnouncement', ['id' => $announcement->getId()]);
	}

	public function delete(Announcement $announcement) :Response
	{
		$this->getEntityManager()->remove($announcement);
		$this->getEntityManager()->flush();

        $this->addFlash('info', 'Announcement deleted successfully');
        return $this->redirectToRoute('announcements');
	}
}

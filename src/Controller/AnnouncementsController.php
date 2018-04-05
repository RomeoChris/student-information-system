<?php

namespace App\Controller;


use App\Entity\Announcement;
use DateTime;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class AnnouncementsController extends DefaultController
{
	public function index() :Response
	{
		return $this->render('announcements/index.html.twig', [
            'pageTitle' => 'All announcements'
        ]);
	}

	public function new(Request $request) :Response
	{
        $title = $request->request->get('title');
        $author = $this->getUser()->getUsername();
        $message = $request->request->get('message');
        $errorList = [];

		if ($request->isMethod('post'))
		{
			if (empty(($title || $message)))
				$errorList[] = 'All fields are required';

			if (empty($errorList))
			{
			    $announcement = new Announcement();
			    $announcement->setTitle($title);
			    $announcement->setAuthor($author);
			    $announcement->setMessage($message);
			    $announcement->setDateCreated(new DateTime());

			    $this->getEntityManager()->persist($announcement);
			    $this->getEntityManager()->flush();

                $this->addFlash('success', 'Announcement added');
                return $this->redirectToRoute('newAnnouncement');
			}
		}

		return $this->render('announcements/new.html.twig', [
            'title' => $title,
            'errors' => $errorList,
            'message' => $message,
            'pageTitle' => 'Add new announcement'
        ]);
	}

	public function view(Announcement $announcement) :Response
	{
        return $this->render('announcements/view.html.twig', [
            'id' => $announcement->getId(),
            'title' => $announcement->getTitle(),
            'message' => $announcement->getMessage(),
            'pageTitle' => $announcement->getTitle(),
        ]);
	}

	public function edit(Announcement $announcement, Request $request) :Response
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
                $announcement->setAuthor($author);
                $announcement->setTitle($title);
                $announcement->setMessage($message);
                $announcement->setDateModified(new DateTime());

                $this->getEntityManager()->persist($announcement);
                $this->getEntityManager()->flush();

                $this->addFlash('success', 'Announcement updated successfully');
                return $this->redirectToRoute('editAnnouncement', ['id' => $announcement->getId()]);
            }
        }

        return $this->render('announcements/edit.html.twig', [
            'id' => $announcement->getId(),
            'title' => $announcement->getTitle(),
            'errors' => $errorList,
            'message' => $announcement->getMessage(),
            'pageTitle' => 'Update announcement ' . $announcement->getId(),
        ]);
	}

	public function delete(Announcement $announcement) :Response
	{
		$this->getEntityManager()->remove($announcement);
		$this->getEntityManager()->flush();

        $this->addFlash('info', 'Announcement deleted successfully');
        return $this->redirectToRoute('announcements');
	}
}

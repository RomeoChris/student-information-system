<?php

namespace App\Controller;


use App\Entity\Announcement;
use DateTime;
use Symfony\Component\HttpFoundation\Response;

class AnnouncementsController extends DefaultController
{
	public function index() :Response
	{
	    if (!$this->getAuthenticator()->isLoggedIn())
	        return $this->redirectToRoute('index');

		return $this->renderTemplate('announcements/index.html.twig', [
            'success' => $this->getSession()->flash('deleteSuccess'),
            'pageTitle' => 'All announcements'
        ]);
	}

	public function new() :Response
	{
        if (!$this->getAuthenticator()->isLoggedIn())
            return $this->redirectToRoute('index');

	    $this->getAuthenticator()->requireLecturer();

        $title = $this->getPost()->get('title');
        $author = $this->getProfile()->getUsername();
        $message = $this->getPost()->get('message');
        $errorList = [];

		if ($this->getRequest()->isMethod('post'))
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

                $this->getSession()->set('announcementSuccess', 'Announcement added');
                return $this->redirectToRoute('newAnnouncement');
			}
		}

		return $this->renderTemplate('announcements/new.html.twig', [
            'title' => $title,
            'errors' => $errorList,
            'success' => $this->getSession()->flash('announcementSuccess'),
            'message' => $message,
            'pageTitle' => 'Add new announcement'
        ]);
	}

	public function view(Announcement $announcement) :Response
	{
        if (!$this->getAuthenticator()->isLoggedIn())
            return $this->redirectToRoute('index');

        return $this->renderTemplate('announcements/view.html.twig', [
            'id' => $announcement->getId(),
            'title' => $announcement->getTitle(),
            'message' => $announcement->getMessage(),
            'pageTitle' => $announcement->getTitle(),
        ]);
	}

	public function edit(Announcement $announcement) :Response
	{
        if (!$this->getAuthenticator()->isLoggedIn())
            return $this->redirectToRoute('index');

        $title = $this->getPost()->get('title');
        $author = $this->getProfile()->getUsername();
        $message = $this->getPost()->get('message');
        $errorList = [];

        if ($this->getRequest()->isMethod('post'))
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

                $this->getSession()->set('editSuccess', 'Announcement updated successfully');
                return $this->redirectToRoute('editAnnouncement', ['id' => $announcement->getId()]);
            }
        }

        return $this->renderTemplate('announcements/edit.html.twig', [
            'id' => $announcement->getId(),
            'title' => $announcement->getTitle(),
            'errors' => $errorList,
            'message' => $announcement->getMessage(),
            'success' => $this->getSession()->flash('editSuccess'),
            'pageTitle' => 'Update announcement ' . $announcement->getId(),
        ]);
	}

	public function delete(Announcement $announcement) :Response
	{
        if (!$this->getAuthenticator()->isLoggedIn())
            return $this->redirectToRoute('index');

		$this->getAuthenticator()->requireLecturer();

		$this->getEntityManager()->remove($announcement);
		$this->getEntityManager()->flush();

        $this->getSession()->set('deleteSuccess', 'Announcement deleted successfully');
        return $this->redirectToRoute('announcements');
	}
}

<?php

namespace App\Pages;


use App\Controller\AppController;
use Symfony\Component\HttpFoundation\Response;

class Announcement extends AppController
{
    private $post;
    private $announcementStorage;

	public function __construct()
	{
	    $this->post = $this->getPost();
	    $this->announcementStorage = $this->getStorageManager()->getAnnouncementStorage();
	}

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
		$errorList = [];

		if ($this->getRequest()->isMethod('post'))
		{
		    $date = date('Y-m-d h:i:sa');
			$title = $this->post->get('title');
			$author = $this->getProfile()->getUsername();
			$message = $this->post->get('message');

			if (empty(($title || $message)))
				$errorList[] = 'All fields are required';

			if (empty($errorList))
			{
			    $announcement = new \App\Models\Announcement(0, $title, $author, $message, $date);
				if ($this->announcementStorage->save($announcement))
				{
					$this->getSession()->set('announcementSuccess', 'Announcement added');
					return $this->redirectToRoute('newAnnouncement');
				}
				else
					$errorList[] = 'Internal server error. Please try again later';
			}
		}

		return $this->renderTemplate('announcements/new.html.twig', [
            'title' => $this->post->get('title'),
            'errors' => $errorList,
            'success' => $this->getSession()->flash('announcementSuccess'),
            'message' => $this->post->get('message'),
            'pageTitle' => 'Add new announcement'
        ]);
	}

	public function view($id = 0) :Response
	{
        if (!$this->getAuthenticator()->isLoggedIn())
            return $this->redirectToRoute('index');

		$announcement = $this->getAnnouncement((int)$id ?? 0);

		if ($announcement->isSaved())
			return $this->renderTemplate('announcements/view.html.twig', [
                'id' => $announcement->getIdentifier(),
                'title' => $announcement->getTitle(),
                'message' => $announcement->getMessage(),
                'pageTitle' => $announcement->getTitle(),
            ]);
		return $this->redirectToRoute('announcements');
	}

	public function edit($id = 0) :Response
	{
        if (!$this->getAuthenticator()->isLoggedIn())
            return $this->redirectToRoute('index');

        $this->getAuthenticator()->requireLecturer();

		$errorList = [];
		$announcement = $this->getAnnouncement((int)$id ?? 0);

		if ($announcement->isSaved())
		{
            if ($this->getRequest()->isMethod('post'))
            {
                $date = date('Y-m-d h:i:sa');
                $title = $this->post->get('title');
                $author = $this->getProfile()->getUsername();
                $message = $this->post->get('message');

                if (empty(($title || $message)))
                    $errorList[] = 'All fields are required';

                if (empty($errorList))
                {
                    $announcement->setAuthor($author);
                    $announcement->setTitle($title);
                    $announcement->setMessage($message);
                    $announcement->setDateUpdated($date);

                    if ($this->announcementStorage->save($announcement))
                    {
                        $this->getSession()->set('editSuccess', 'Announcement updated successfully');
                        return $this->redirectToRoute('editAnnouncement', ['id' => $announcement->getIdentifier()]);
                    }
                    else
                        $errorList[] = 'Internal server error. Please try again later';
                }
            }

            return $this->renderTemplate('announcements/edit.html.twig', [
                'id' => $announcement->getIdentifier(),
                'title' => $announcement->getTitle(),
                'errors' => $errorList,
                'message' => $announcement->getMessage(),
                'success' => $this->getSession()->flash('editSuccess'),
                'pageTitle' => 'Update announcement ' . $announcement->getIdentifier(),
            ]);
        }
        return $this->redirectToRoute('announcements');
	}

	public function delete($id = 0) :Response
	{
        if (!$this->getAuthenticator()->isLoggedIn())
            return $this->redirectToRoute('index');

		$this->getAuthenticator()->requireLecturer();
		
		$announcement = $this->getAnnouncement((int)$id ?? 0);

		if ($announcement->isSaved())
		{
			if ($this->announcementStorage->delete($announcement))
			{
			    $this->getSession()->set('deleteSuccess', 'Announcement deleted successfully');
				return $this->redirectToRoute('announcements');
			}
            return $this->redirectToRoute('announcements');
		}
		return $this->redirectToRoute('announcements');
	}

	private function getAnnouncement(int $id = 0) :\App\Models\Announcement
	{
		return $this->announcementStorage->getById($id);
	}
}

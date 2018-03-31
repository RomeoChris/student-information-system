<?php

namespace App\Controller;


use App\Entity\Complaint;
use DateTime;
use Symfony\Component\HttpFoundation\Response;

class ComplaintsController extends DefaultController
{
    public function index() :Response
    {
        if (!$this->getAuthenticator()->isLoggedIn())
            return $this->redirectToRoute('index');

        $this->getAuthenticator()->requireAdmin();

        return $this->renderTemplate('complaints/index.html.twig', [
            'success' => $this->getSession()->flash('deleteSuccess'),
            'pageTitle' => 'All Complaints'
        ]);
    }

    public function new() :Response
    {
        if (!$this->getAuthenticator()->isLoggedIn())
            return $this->redirectToRoute('index');

        if ($this->getAuthenticator()->isLecturer())
            return $this->redirectToRoute('complaints');

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
                $complaint = new Complaint();
                $complaint->setTitle($title);
                $complaint->setAuthor($author);
                $complaint->setMessage($message);
                $complaint->setDateCreated(new DateTime());

                $this->entityManager->persist($complaint);
                $this->entityManager->flush();

                $this->getSession()->set('saveSuccess', 'Complaint has been sent to our admins. Thanks');
                return $this->redirectToRoute('newComplaint');
            }
        }

        return $this->renderTemplate('complaints/new.html.twig', [
            'title' => $title,
            'errors' => $errorList,
            'message' => $message,
            'success' => $this->getSession()->flash('saveSuccess'),
            'pageTitle' => 'Add new complaint',
        ]);
    }

    public function view(Complaint $complaint) :Response
    {
        if (!$this->getAuthenticator()->isLoggedIn())
            return $this->redirectToRoute('index');

        $this->getAuthenticator()->requireAdmin();

        return $this->renderTemplate('complaints/view.html.twig', [
            'id' => $complaint->getId(),
            'title' => $complaint->getTitle(),
            'author' => $complaint->getAuthor(),
            'message' => $complaint->getMessage(),
            'pageTitle' => $complaint->getTitle()
        ]);
    }

    public function delete(Complaint $complaint) :Response
    {
        if (!$this->getAuthenticator()->isLoggedIn())
            return $this->redirectToRoute('index');

        $this->getAuthenticator()->requireAdmin();

        $this->entityManager->remove($complaint);
        $this->entityManager->flush();

        $this->getSession()->set('deleteSuccess', 'Complaint deleted successfully');
        return $this->redirectToRoute('complaints');
    }
}

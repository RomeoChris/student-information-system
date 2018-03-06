<?php

namespace App\Pages;


use App\Models\Complaint;
use App\Controller\AppController;
use Symfony\Component\HttpFoundation\Response;

class Complaints extends AppController
{
    private $post;
    private $complaintStorage;

    public function __construct()
    {
        $this->post = $this->getPost();
        $this->complaintStorage = $this->getStorageManager()->getComplaintStorage();
    }

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

        $errorList = [];

        $date = date('Y-m-d h:i:sa');
        $title = $this->post->get('title');
        $author = $this->getProfile()->getUsername();
        $message = $this->post->get('message');

        if ($this->getRequest()->isMethod('post'))
        {
            if (empty(($title || $message)))
                $errorList[] = 'All fields are required';

            if (empty($errorList))
            {
                $complaint = new Complaint(0, $title, $author, $message, $date);

                if ($this->complaintStorage->save($complaint))
                {
                    $this->getSession()->set('success', 'Complaint has been sent to our admins. Thanks');
                    return $this->redirectToRoute('newComplaint');
                }
                else
                    $errorList[] = 'Internal server error. Please try again later';
            }
        }

        return $this->renderTemplate('complaints/new.html.twig', [
            'title' => $title,
            'errors' => $errorList,
            'message' => $message,
            'success' => $this->getSession()->flash('success'),
            'pageTitle' => 'Add new complaint',
        ]);
    }
}

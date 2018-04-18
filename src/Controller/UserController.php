<?php

namespace App\Controller;


use App\Form\UserType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class UserController extends DefaultController
{
    public function index() :Response
    {
        return $this->render('users/index.html.twig', ['pageTitle' => 'All users']);
    }

    public function new(Request $request, EntityManagerInterface $entityManager)
    {

    }

    public function profile(Request $request) :Response
    {
        $user = $this->getProfile();
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if (!$form->isSubmitted() || !$form->isValid())
            return $this->render('users/view.html.twig', [
                'form' => $form->createView(),
                'pageTitle' => $user->getUsername() . ' Profile settings',
                'formHeader' => $user->getUsername() . ' Profile settings',
            ]);

        if ($this->getRequest()->isMethod('post'))
        {
            if ($this->updateAction($gender, $user->getGender()))
            {
                if (!(($gender == 'male') || ($gender == 'female')))
                    $errorList[] = 'Invalid gender. use Male or Female';
                $user->setGender($gender);
            }

            if ($this->updateAction($courseId, $user->getCourseId()) && $this->getAuthenticator()->isAdmin())
                $user->setCourseId($courseId);

            if (!empty($password) && $this->getAuthenticator()->isAdmin())
                $user->setPassword($password);

            if (!$this->getAuthenticator()->isAdmin() && (!empty($password) || !empty($oldPassword)))
            {
                if (!password_verify($oldPassword, $user->getPasswordHash()))
                    $errorList[] = 'Wrong old password';
                if ($password != $repeatPassword)
                    $errorList[] = 'Passwords do not match';
                $user->setPassword($password);
            }

            $this->addFlash('success', 'Profile has been successfully updated');
            return $this->redirectToRoute('updateUser', ['id' => $user->getId()]);
        }
    }

    private function updateAction(string $oldValue = '', string $newValue = null) :bool
    {
        return $oldValue != $newValue;
    }
}

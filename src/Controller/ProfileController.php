<?php

namespace App\Controller;


use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class ProfileController extends DefaultController
{
    public function index() :Response
    {
        return $this->render('users/index.html.twig', ['pageTitle' => 'All users']);
    }

    public function new(Request $request, EntityManagerInterface $entityManager)
    {
        $this->addFlash('success', 'User has been successfully added');
        $this->redirectToRoute('newUser');
        return $this->render('users/new.html.twig', [
            'pageTitle' => 'Add new user to system',
        ]);
    }

    public function profile() :Response
    {
        $errorList = [];

        if ($this->getRequest()->isMethod('post'))
        {
            if ($this->updateAction($role, $user->getRole()) && $this->getAuthenticator()->isAdmin())
                $user->setRole($role);

            if ($this->updateAction($email, $user->getEmail()))
            {
                if ($this->findUserByEmail($email))
                    $errorList[] = 'Email already taken';
                $user->setEmail($email);
            }

            if ($this->updateAction($gender, $user->getGender()))
            {
                if (!(($gender == 'male') || ($gender == 'female')))
                    $errorList[] = 'Invalid gender. use Male or Female';
                $user->setGender($gender);
            }

            if ($this->updateAction($courseId, $user->getCourseId()) && $this->getAuthenticator()->isAdmin())
                $user->setCourseId($courseId);

            if ($this->updateAction($username, $user->getUsername()))
            {
                if ($this->findUserByName($username))
                    $errorList[] = 'Username already taken';
                $user->setUsername($username);
            }

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

            if ($this->updateAction($lastName, $user->getLastName()) && $this->getAuthenticator()->isAdmin())
                $user->setLastName($lastName);

            if ($this->updateAction($firstName, $user->getFirstName()) && $this->getAuthenticator()->isAdmin())
                $user->setFirstName($firstName);

            if ($this->updateAction($phoneNumber, $user->getPhoneNumber()))
                $user->setPhoneNumber($phoneNumber);

            if (empty($errorList))
            {
                $this->entityManager->persist($user);
                $this->entityManager->flush();

                $this->getSession()->set('updateUserSuccess', 'Profile has been successfully updated');
                return $this->redirectToRoute('updateUser', ['id' => $user->getId()]);
            }
        }

        return $this->renderTemplate('users/view.html.twig', [
            'id' => $user->getId(),
            'role' => $user->getRole(),
            'email' => $user->getEmail(),
            'errors' => $errorList,
            'gender' => $user->getGender(),
            'course' => $this->getCourse($user->getCourseId())->getName(),
            'courseId' => $user->getCourseId(),
            'courses' => $this->getCourseRepository()->findAll(),
            'success' => $this->getSession()->flash('updateUserSuccess'),
            'disabled' => $disabled,
            'username' => $user->getUsername(),
            'lastName' => $user->getLastName(),
            'firstName' => $user->getFirstName(),
            'pageTitle' => 'Profile Settings',
            'phoneNumber' => $user->getPhoneNumber(),
        ]);
    }

    private function updateAction(string $oldValue = '', string $newValue = null) :bool
    {
        return $oldValue != $newValue;
    }
}

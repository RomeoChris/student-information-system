<?php

namespace App\Controller;


use App\Entity\Profile;
use DateTime;
use Symfony\Component\HttpFoundation\Response;

class ProfileController extends DefaultController
{
    public function index() :Response
    {
        if (!$this->getAuthenticator()->isLoggedIn())
            return $this->redirectToRoute('index');

        $this->getAuthenticator()->requireLecturer();

        return $this->renderTemplate('users/index.html.twig', [
            'pageTitle' => 'All users'
        ]);
    }

    public function new()
    {
        if (!$this->getAuthenticator()->isLoggedIn())
            return $this->redirectToRoute('index');

        $this->getAuthenticator()->requireAdmin();

        $role = $this->getPost()->get('role');
        $email = $this->getPost()->get('email');
        $gender = $this->getPost()->get('gender');
        $courseId = $this->getPost()->get('courseId');
        $username = strtolower($this->getPost()->get('username'));
        $password = $this->getPost()->get('password');
        $lastName = $this->getPost()->get('last_name');
        $firstName = $this->getPost()->get('first_name');
        $phoneNumber = $this->getPost()->get('phone_number');

        $errorList = [];

        if ($this->getRequest()->isMethod('post'))
        {

            $fields = [
                $username, $email, $password, $firstName,
                $lastName, $phoneNumber, $role, $gender,
            ];

            if ($this->findUserByName($username))
                $errorList[] = 'Username already taken';

            if ($this->findUserByEmail($email))
                $errorList[] = 'Email already taken';

            if (count($fields))
                foreach ($fields as $field)
                    if (empty($field))
                    {
                        $errorList[] = 'All fields with * are required';
                        break;
                    }

            if ($role == ('admin' || 'head') && !$this->getAuthenticator()->isHeadAdmin())
                $role = 'student';

            if (count($errorList) === 0)
            {
                $profile = new Profile();
                $profile->setRole($role);
                $profile->setEmail($email);
                $profile->setGender($gender);
                $profile->setUsername($username);
                $profile->setPassword($password);
                $profile->setCourseId($courseId);
                $profile->setLastName($lastName);
                $profile->setFirstName($firstName);
                $profile->setPhoneNumber($phoneNumber);
                $profile->setDateCreated(new DateTime());

                $this->entityManager->persist($profile);
                $this->entityManager->flush();

                $this->getSession()->set('successAddUser', 'User has been successfully added');
                return $this->redirectToRoute('newUser');
            }
        }

        return $this->renderTemplate('users/new.html.twig', [
            'errors' => $errorList,
            'courses' => $this->getCourseRepository()->findAll(),
            'success' => $this->getSession()->flash('successAddUser'),
            'pageTitle' => 'Add new user to system',
        ]);
    }

    public function profile(Profile $profile) :Response
    {
        if (!$this->getAuthenticator()->isLoggedIn())
            return $this->redirectToRoute('index');

        $errorList = [];

        if ($this->getProfile()->getRole() == 'student'
            && $this->getProfile()->getId() != $profile->getId())
            return $this->redirectToRoute('updateUser', ['id' => $this->getProfile()->getId()]);

        $disabled = 'disabled';

        if ($this->getAuthenticator()->isLecturer()
            && $profile->getId() == $this->getProfile()->getId())
            $disabled = '';

        if ($this->getAuthenticator()->isAdmin())
            $disabled = '';

        $role = $this->getPost()->get('role', $profile->getRole());
        $email = $this->getPost()->get('email', $profile->getEmail());
        $gender = $this->getPost()->get('gender', $profile->getGender());
        $courseId = $this->getPost()->get('courseId', $profile->getCourseId());
        $username = strtolower($this->getPost()->get('username', $profile->getUsername()));
        $password = $this->getPost()->get('password', '');
        $lastName = $this->getPost()->get('last_name', $profile->getLastName());
        $firstName = $this->getPost()->get('first_name', $profile->getFirstName());
        $phoneNumber = $this->getPost()->get('phone_number', $profile->getPhoneNumber());
        $oldPassword = $this->getPost()->get('old_password', '');
        $repeatPassword = $this->getPost()->get('repeat_password', '');

        if ($this->getRequest()->isMethod('post'))
        {
            if ($this->updateAction($role, $profile->getRole()) && $this->getAuthenticator()->isAdmin())
                $profile->setRole($role);

            if ($this->updateAction($email, $profile->getEmail()))
            {
                if ($this->findUserByEmail($email))
                    $errorList[] = 'Email already taken';
                $profile->setEmail($email);
            }

            if ($this->updateAction($gender, $profile->getGender()))
            {
                if (strtolower($gender) != 'male' || strtolower($gender) != 'female')
                    $errorList[] = 'Invalid gender. user Male or Female';
                $profile->setGender($gender);
            }

            if ($this->updateAction($courseId, $profile->getCourseId()) && $this->getAuthenticator()->isAdmin())
                $profile->setCourseId($courseId);

            if ($this->updateAction($username, $profile->getUsername()))
            {
                if ($this->findUserByName($username))
                    $errorList[] = 'Username already taken';
                $profile->setUsername($username);
            }

            if (!empty($password) && $this->getAuthenticator()->isAdmin())
                $profile->setPassword($password);

            if (!$this->getAuthenticator()->isAdmin() && (!empty($password) || !empty($oldPassword)))
            {
                if (!password_verify($oldPassword, $profile->getPasswordHash()))
                    $errorList[] = 'Wrong old password';
                if ($password != $repeatPassword)
                    $errorList[] = 'Passwords do not match';
                $profile->setPassword($password);
            }

            if ($this->updateAction($lastName, $profile->getLastName()) && $this->getAuthenticator()->isAdmin())
                $profile->setLastName($lastName);

            if ($this->updateAction($firstName, $profile->getFirstName()) && $this->getAuthenticator()->isAdmin())
                $profile->setFirstName($firstName);

            if ($this->updateAction($phoneNumber, $profile->getPhoneNumber()))
                $profile->setPhoneNumber($phoneNumber);

            if (empty($errorList))
            {
                $this->entityManager->persist($profile);
                $this->entityManager->flush();

                $this->getSession()->set('updateUserSuccess', 'Profile has been successfully updated');
                return $this->redirectToRoute('updateUser', ['id' => $profile->getId()]);
            }
        }

        return $this->renderTemplate('users/view.html.twig', [
            'id' => $profile->getId(),
            'role' => $profile->getRole(),
            'email' => $profile->getEmail(),
            'errors' => $errorList,
            'gender' => $profile->getGender(),
            'course' => $profile->getCourseId(),
            'courses' => $this->getCourseRepository()->findAll(),
            'success' => $this->getSession()->flash('updateUserSuccess'),
            'disabled' => $disabled,
            'username' => $profile->getUsername(),
            'lastName' => $profile->getLastName(),
            'firstName' => $profile->getFirstName(),
            'pageTitle' => 'Profile Settings',
            'phoneNumber' => $profile->getPhoneNumber(),
        ]);
    }

    private function findUserByName(string $name = '') :bool
    {
        $user = $this->getProfileRepository()->findOneBy(['username' => $name]);
        return $this->entityManager->contains($user);
    }

    private function findUserByEmail(string $email = '') :bool
    {
        $user = $this->getProfileRepository()->findOneBy(['email' => $email]);
        return $this->entityManager->contains($user);
    }

    private function updateAction(string $oldValue = '', string $newValue = null) :bool
    {
        return $oldValue != $newValue;
    }
}

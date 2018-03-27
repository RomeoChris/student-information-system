<?php

namespace App\Pages;


use App\Controller\AppController;
use App\Core\Collection\AppCollection;
use App\Models\IModel;
use App\Models\Profile;
use Symfony\Component\HttpFoundation\Response;

class Users extends AppController
{
    private $session;
    private $courseStorage;
    private $profileStorage;

    public function __construct()
    {
        $this->session = $this->getSession();
        $this->courseStorage = $this->getStorageManager()->getCourseStorage();
        $this->profileStorage = $this->getStorageManager()->getProfileStorage();
    }

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
        $date = date('Y-m-d h:i:sa');
        $email = $this->getPost()->get('email');
        $gender = $this->getPost()->get('gender');
        $course = $this->getPost()->get('course');
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

            if ($this->findUserByName($username)->isSaved())
                $errorList[] = 'Username already taken';

            if ($this->findUserByEmail($email)->isSaved())
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
                $profile = new Profile(
                    0,
                    $username,
                    $email,
                    $firstName,
                    $lastName,
                    password_hash($password, 1),
                    $gender,
                    $phoneNumber,
                    $course,
                    $role,
                    $date
                );

                if ($this->profileStorage->save($profile))
                {
                    $this->session->set('successAddUser', 'User has been successfully added');
                    return $this->redirectToRoute('newUser');
                }
                else
                    $errorList[] = 'Internal server error. Please try again later';
            }
        }

        return $this->renderTemplate('users/new.html.twig', [
            'errors' => $errorList,
            'courses' => $this->getCourses(),
            'success' => $this->session->flash('successAddUser'),
            'pageTitle' => 'Add new user to system',
        ]);
    }

    public function profile($id = 0) :Response
    {
        if (!$this->getAuthenticator()->isLoggedIn())
            return $this->redirectToRoute('index');

        $errorList = [];

        /* @var $profile Profile */
        $profile = $this->profileStorage->getById((int)$id ?? 0);

        if (!$profile->isSaved())
            return $this->redirectToRoute('updateUser', ['id' => $this->getProfile()->getIdentifier()]);

        if ($this->getProfile()->getRole() == 'student'
            && $this->getProfile()->getIdentifier() != $profile->getIdentifier())
            return $this->redirectToRoute('updateUser', ['id' => $this->getProfile()->getIdentifier()]);

        $disabled = 'disabled';

        if ($this->getAuthenticator()->isLecturer()
            && $profile->getIdentifier() == $this->getProfile()->getIdentifier())
            $disabled = '';

        if ($this->getAuthenticator()->isAdmin())
            $disabled = '';

        $role = $this->getPost()->get('role', $profile->getRole());
        $email = $this->getPost()->get('email', $profile->getEmail());
        $gender = $this->getPost()->get('gender', $profile->getGender());
        $course = $this->getPost()->get('course', $profile->getCourse());
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
                if ($this->findUserByEmail($email)->isSaved())
                    $errorList[] = 'Email already taken';
                $profile->setEmail($email);
            }

            if ($this->updateAction($gender, $profile->getGender()))
            {
                if (strtolower($gender) != 'male' || strtolower($gender) != 'female')
                    $errorList[] = 'Invalid gender. user Male or Female';
                $profile->setGender($gender);
            }

            if ($this->updateAction($course, $profile->getCourse()) && $this->getAuthenticator()->isAdmin())
                $profile->setCourse($course);

            if ($this->updateAction($username, $profile->getUsername()))
            {
                if ($this->findUserByName($username)->isSaved())
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
                if ($this->profileStorage->save($profile))
                {
                    $this->session->set('updateUserSuccess', 'Profile has been successfully updated');
                    return $this->redirectToRoute('updateUser', ['id' => $profile->getIdentifier()]);
                }
            }
        }

        return $this->renderTemplate('users/view.html.twig', [
            'id' => $profile->getIdentifier(),
            'role' => $profile->getRole(),
            'email' => $profile->getEmail(),
            'errors' => $errorList,
            'gender' => $profile->getGender(),
            'course' => $profile->getCourse(),
            'success' => $this->session->flash('updateUserSuccess'),
            'disabled' => $disabled,
            'username' => $profile->getUsername(),
            'lastName' => $profile->getLastName(),
            'firstName' => $profile->getFirstName(),
            'pageTitle' => 'Profile Settings',
            'phoneNumber' => $profile->getPhoneNumber(),
        ]);
    }

    private function getCourses() :array
    {
        $courses = $this->courseStorage->getAll();
        $courseNames = [];
        foreach ($courses as $course)
            $courseNames[] = $course['name'];
        return $courseNames;
    }

    private function findUserByName(string $name = '') :IModel
    {
        $where = 'username = ?';
        $data = new AppCollection($this->profileStorage->getWhere($where, [$name]));
        return $this->profileStorage->convertToModel($data);
    }

    private function findUserByEmail(string $email = '') :IModel
    {
        $where = 'email = ?';
        $data = new AppCollection($this->profileStorage->getWhere($where, [$email]));
        return $this->profileStorage->convertToModel($data);
    }

    private function updateAction(string $oldValue = '', string $newValue = null) :bool
    {
        return $oldValue != $newValue;
    }
}

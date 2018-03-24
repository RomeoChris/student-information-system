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

        return $this->renderTemplate('users/index.html.twig', [
            'pageTitle' => 'All users'
        ]);
    }

    public function new()
    {
        $role = $this->getPost()->get('role');
        $date = date('Y-m-d h:i:sa');
        $email = $this->getPost()->get('email');
        $gender = $this->getPost()->get('gender');
        $course = $this->getPost()->get('course');
        $username = strtolower($this->getPost()->get('username'));
        $password = $this->getPost()->get('password');
        $lastName = $this->getPost()->get('last_name');
        $firstName = $this->getPost()->get('first_name');
        $nationality = $this->getPost()->get('nationality');
        $phoneNumber = $this->getPost()->get('phone_number');

        $errorList = [];

        if ($this->getRequest()->isMethod('post'))
        {

            $fields = [
                $username, $email, $password, $firstName,
                $lastName, $nationality, $phoneNumber, $role, $gender,
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
                    $nationality,
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
}

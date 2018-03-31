<?php

namespace App\Pages;


use App\Controller\AppController;
use Symfony\Component\HttpFoundation\Response;

class Login extends AppController
{
    public function index() :Response
    {
        if ($this->getAuthenticator()->isLoggedIn())
            return $this->redirectToRoute('dashboard');

        $errorList = [];
        $token = $this->getPost()->get('token', '');
        $username = $this->getPost()->get('username', '');
        $password = $this->getPost()->get('password', '');

        if ($this->getRequest()->isMethod('post'))
        {
            if ($this->getToken()->validate('loginToken', $token))
            {
                if (empty($username))
                    $errorList[] = 'Please enter your login';
                if (empty($password))
                    $errorList[] = 'Please enter your password';
                if (empty($errorList) && $this->getAuthenticator()->login($username, $password))
                    return $this->redirectToRoute('dashboard');
                else
                    $errorList[] = 'Invalid login or password';
            }
            else
                $errorList[] = 'Failed to authorize login. Try reloading page';
        }

        return $this->render('login.html.twig', [
            'token' => $this->getToken()->generate('loginToken'),
            'errors' => $errorList,
            'username' => $username,
            'pageTitle' => 'Login | SIS',
            'brandName' => 'SIS',
        ]);
    }
}

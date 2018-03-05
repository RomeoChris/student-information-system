<?php

namespace App\Pages;


use App\Controller\AppController;
use Symfony\Component\HttpFoundation\Response;

class Login extends AppController
{
    private $post;
    private $token;
    private $authenticator;

    public function __construct()
    {
        $this->post = $this->getPost();
        $this->token = $this->getToken();
        $this->authenticator = $this->getAuthenticator();
    }

    public function index() :Response
    {
        $errorList = [];

        if ($this->getAuthenticator()->isLoggedIn())
            return $this->redirectToRoute('dashboard');

        if ($this->getRequest()->isMethod('post'))
        {
            if ($this->token->validate('loginToken', $this->post->get('token')))
            {
                $username = $this->post->get('username');
                $password = $this->post->get('password');

                if (empty($username))
                    $errorList[] = 'Please enter your login';
                if (empty($password))
                    $errorList[] = 'Please enter your password';
                if (empty($errorList) && $this->authenticator->login($username, $password))
                    return $this->redirectToRoute('dashboard');
                else
                    $errorList[] = 'Invalid login or password';
            }
            else
            {
                $errorList[] = 'Failed to authorize login. Try reloading page';
            }
        }

        return $this->render('login.html.twig', [
            'token' => $this->token->generate('loginToken'),
            'errors' => $errorList,
            'username' => $this->post->get('username'),
            'pageTitle' => 'Login | SIS',
            'brandName' => 'SIS',
        ]);
    }
}

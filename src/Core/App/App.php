<?php

namespace App\Core\App;


use App\Core\Authentication\Authenticator;
use App\Core\Configuration\Configuration;
use App\Core\Database\Database;
use App\Core\Profile\IUser;
use App\Core\Profile\Profile;
use App\Core\Profile\StudentProfile;
use App\Core\Profile\User;
use App\Core\Request\IRequest;
use App\Core\Request\RequestData;
use App\Core\Server\Server;
use App\Core\Session\Session;

class App implements IApp
{
    private $request;
    private $configuration;
    private $database;
    private $rootPath;
    private $webPaths;

    public function __construct(
        IRequest $request,
        Configuration $configuration,
        Database $database,
        string $rootPath,
        array $webPaths
    )
    {
        $this->request = $request;
        $this->configuration = $configuration;
        $this->database = $database;
        $this->rootPath = $rootPath;
        $this->webPaths = $webPaths;
    }

    public function getRequest() :IRequest
    {
        return $this->request;
    }

    public function getConfiguration() :Configuration
    {
        return $this->configuration;
    }

    public function getSession() :Session
    {
        return new Session;
    }

    public function getDatabase() :Database
    {
        return $this->database;
    }

    public function getServer() :Server
    {
        return new Server;
    }

    public function getRootPath() :string
    {
        return $this->rootPath;
    }

    public function getSourcePath() :string
    {
        return $this->getRootPath() . 'src' . DIRECTORY_SEPARATOR;
    }

    public function getTemplatesPath() :string
    {
         return $this->getSourcePath() . 'templates' . DIRECTORY_SEPARATOR;
    }

    public function getIncludesPath() :string
    {
        return $this->getSourcePath() . 'Lib' . DIRECTORY_SEPARATOR;
    }

    public function getControllerPath() :string
    {
        return $this->getSourcePath() . 'Pages' . DIRECTORY_SEPARATOR;
    }

    public function getWebPaths() :RequestData
    {
        return new RequestData($this->webPaths);
    }

    public function getStudentProfile() :StudentProfile
    {
        return new StudentProfile($this->getUser(), $this->getDatabase(), $this->getSession());
    }

    public function getProfile() :Profile
    {
        return new Profile($this->getUser(), $this->getSession());
    }

    public function getUser() :IUser
    {
        return new User($this->getDatabase(), $this->getSession());
    }

    public function getAuthenticator() :Authenticator
    {
        return new Authenticator($this->getSession());
    }

    public function getDefinitions() :array
    {
        $definitions = [
            'footNote' => date('Y') . ' © Student Information System.',
            'brandName' => 'SIS',
            'brandName2' => 'Student Information System',
            'js' => $this->getWebPaths()->get('js'),
            'css' => $this->getWebPaths()->get('css'),
            'images' => $this->getWebPaths()->get('images'),
            'username' => $this->getProfile()->getUsername(),
            'admin' => $this->getAuthenticator()->isAdmin(),
            'student' => $this->getAuthenticator()->isStudent(),
            'role' => $this->getProfile()->getRole()
        ];
        return $definitions;
    }
}

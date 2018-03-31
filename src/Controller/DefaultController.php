<?php

namespace App\Controller;


use App\Entity\Course;
use App\Entity\Department;
use App\Entity\Profile;
use App\Core\Token\Token;
use App\Core\Database\Database;
use App\Core\Session\AppSession;
use App\Core\DataTable\DataTable;
use App\Core\Collection\AppCollection;
use App\Core\Configuration\Configuration;
use App\Core\Authenticator\Authenticator;
use DateTime;
use Doctrine\Common\Persistence\ObjectRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\ServerBag;
use Symfony\Component\HttpFoundation\ParameterBag;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class DefaultController extends AbstractController
{
	protected static $ds = DIRECTORY_SEPARATOR;
	public $entityManager;

	public function __construct(
	    EntityManagerInterface $entityManager
    )
    {
        $this->entityManager = $entityManager;
    }

    public function getEntityManager() :EntityManagerInterface
    {
        return $this->entityManager;
    }

    protected function getRequest() :Request
	{
		return Request::createFromGlobals();
	}

	protected function getPost() :ParameterBag
	{
		return $this->getRequest()->request;
	}

	protected function getGet() :ParameterBag
	{
		return $this->getRequest()->query;
	}

	protected function getSession() :AppSession
	{
        if (!isset($_SESSION))
            session_start();
        return new AppSession;
	}

	protected function getServer() :ServerBag
	{
		return $this->getRequest()->server;
	}

	protected function getConfiguration() :Configuration
	{
		return new Configuration($this->getRootDir());
	}

	protected function getDatabase() :Database
	{
		$db = self::getDatabaseConfig();
        $port = $db->getString('port');
		$driver = $db->getString('driver');
		$hostname = $db->getString('hostname');
		$database = $db->getString('database');
		$username = $db->getString('username');
		$password = $db->getString('password');
		$connectionString = "$driver:host=$hostname;dbname=$database;port=$port";
		return Database::getInstance($connectionString, $username, $password);
	}

	protected function getRootDir() :string
	{
		return __DIR__ . self::$ds . '..' . self::$ds . '..' . self::$ds;
	}

	protected function getDatabaseConfig() :AppCollection
	{
		return new AppCollection(self::getConfiguration()->getData()['database']);
	}

	protected function getResponse($content = '', int $status = 200, array $headers = []) :Response
	{
		return new Response($content, $status, $headers);
	}

	protected function getAuthenticator() :Authenticator
	{
		return new Authenticator($this->entityManager, $this->getSession());
	}

	protected function getToken() :Token
    {
        return new Token($this->getSession());
    }

	protected function getProfile() :Profile
    {
        $userId = $this->getSession()->getInt('identifier');
        return $this->findProfileById($userId);
    }

    protected function findProfileById(int $id = 0) :Profile
    {
        /** @var $profile Profile */
        $profile = $this->getDoctrine()->getRepository(Profile::class)->find($id);
        return $profile;
    }

    protected function renderTemplate(string $view = '', array $parameters = []) :Response
    {
        return $this->render($view, array_merge($parameters, $this->commonParameters()));
    }

    protected function getDataTable() :DataTable
    {
        return new DataTable($this->getDatabaseConfig());
    }

    public function logout() :Response
    {
        $profile = $this->getProfile();

        if ($this->getEntityManager()->contains($profile))
        {
            $profile->setLastLogin(new DateTime());
            $this->getEntityManager()->persist($profile);
            $this->getEntityManager()->flush();
        }
        $this->getSession()->destroy();
        return $this->redirectToRoute('index');
    }

    public function dashboard() :Response
    {
        if (!$this->getAuthenticator()->isLoggedIn())
            return $this->redirectToRoute('index');

        return $this->renderTemplate('dashboard.html.twig', [
            'online' => 2,
            'pageTitle' => 'Dashboard',
            'complaints' => $this->getComplaints(),
            'announcements' => $this->getAnnouncements()
        ]);
    }

    public function timetablesDownload() :Response
    {
        if (!$this->getAuthenticator()->isLoggedIn())
            return $this->redirectToRoute('index');

        return $this->renderTemplate('downloads/timetables.html.twig', [
            'pageTitle' => 'Timetables downloads'
        ]);
    }

    public function notesDownload() :Response
    {
        if (!$this->getAuthenticator()->isLoggedIn())
            return $this->redirectToRoute('index');

        return $this->renderTemplate('downloads/notes.html.twig', [
            'pageTitle' => 'Notes downloads'
        ]);
    }

    public function login() :Response
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

    protected function getProfileRepository() :ObjectRepository
    {
        return $this->entityManager->getRepository(Profile::class);
    }

    protected function getDepartmentRepository() :ObjectRepository
    {
        return $this->entityManager->getRepository(Department::class);
    }

    protected function getCourseRepository() :ObjectRepository
    {
        return $this->entityManager->getRepository(Course::class);
    }

    private function commonParameters() :array
    {
        return [
            'admin' => $this->getAuthenticator()->isAdmin(),
            'student' => $this->getAuthenticator()->isLoggedIn(),
            'footNote' => 'SIS 2017',
            'lecturer' => $this->getAuthenticator()->isLecturer(),
            'headAdmin' => $this->getAuthenticator()->isHeadAdmin(),
            'profileId' => $this->getProfile()->getId(),
            'brandName' => 'SIS',
            'brandName2' => 'SIS',
            'profileRole' => $this->getProfile()->getRole(),
            'numberOfAdmins' => $this->getUsers('admin'),
            'profileUsername' => $this->getProfile()->getUsername(),
            'numberOfStudents' => $this->getUsers('student'),
            'numberOfLecturers' => $this->getUsers('lecturer'),
        ];
    }

    private function getComplaints() :array
    {
        $query = 'SELECT * FROM complaint ORDER BY id DESC LIMIT 3';
        return $this->getDatabase()->fetchAll($query);
    }

    private function getAnnouncements() :array
    {
        $query = 'SELECT * FROM announcement ORDER BY id DESC LIMIT 3';
        return $this->getDatabase()->fetchAll($query);
    }

    private function getUsers(string $role) :int
    {
        $query = 'SELECT * FROM profile WHERE role = ?';
        return $this->getDatabase()->rowCount($query, [$role]);
    }
}

<?php

namespace App\Controller;


use App\Entity\Course;
use App\Entity\Department;
use App\Entity\Profile;
use App\Core\DataTable\DataTable;
use App\Core\Collection\AppCollection;
use App\Core\Configuration\Configuration;
use App\Repository\AnnouncementRepository;
use App\Repository\ComplaintRepository;
use App\Repository\UserRepository;
use Doctrine\Common\Persistence\ObjectRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\ParameterBag;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class DefaultController extends AbstractController
{
    protected static $ds = DIRECTORY_SEPARATOR;
    
    /**
     * @var SessionInterface
     * @deprecated
     */
    public $session;
    
    
    /**
     * @var EntityManagerInterface
     * @deprecated
     */
    public $entityManager;
    
    public function __construct(
        EntityManagerInterface $entityManager,
        SessionInterface $session
    )
    {
        $this->session = $session;
        $this->entityManager = $entityManager;
    }
    
    public function getEntityManager() :EntityManagerInterface
    {
        return $this->entityManager;
    }
    
    /**
     * @return Request
     * @deprecated
     */
    protected function getRequest() :Request
    {
        return Request::createFromGlobals();
    }
    
    /**
     * @return ParameterBag
     * @deprecated
     */
    protected function getPost() :ParameterBag
    {
        return $this->getRequest()->request;
    }
    
    /**
     * @return ParameterBag
     * @deprecated
     */
    protected function getGet() :ParameterBag
    {
        return $this->getRequest()->query;
    }
    
    /**
     * @return SessionInterface
     * @deprecated
     */
    protected function getSession() :SessionInterface
    {
        return $this->session;
    }
    
    protected function getConfiguration() :Configuration
    {
        return new Configuration($this->getRootDir());
    }
 
    protected function getRootDir() :string
    {
        return __DIR__ . self::$ds . '..' . self::$ds . '..' . self::$ds;
    }
    
    protected function getDatabaseConfig() :AppCollection
    {
        return new AppCollection(self::getConfiguration()->getData()['database']);
    }
    
    /**
     * @param int $id
     * @return Profile
     * @deprecated
     */
    protected function findProfileById(int $id = 0) :Profile
    {
        /** @var $profile Profile */
        $profile = $this->getDoctrine()->getRepository(Profile::class)->find($id);
        return $profile;
    }
    
    /**
     * @param string $view
     * @param array $parameters
     * @return Response
     * @deprecated
     */
    protected function renderTemplate(string $view = '', array $parameters = []) :Response
    {
        return $this->render($view, $parameters);
    }
    
    protected function getDataTable() :DataTable
    {
        return new DataTable($this->getDatabaseConfig());
    }
 
    public function dashboard(
        UserRepository $userRepository,
        ComplaintRepository $complaintRepository,
        AnnouncementRepository $announcementRepository) :Response
    {
        return $this->render('dashboard.html.twig', [
            'online' => 2,
            'pageTitle' => 'Dashboard',
            'complaints' => $complaintRepository->getLatestComplaints(3),
            'announcements' => $announcementRepository->getLatestAnnouncements(3),
            'numberOfAdmins' => count($userRepository->getUsersWithRole('ROLE_ADMIN')),
            'numberOfStudents' => count($userRepository->getUsersWithRole('ROLE_USER')),
            'numberOfLecturers' => count($userRepository->getUsersWithRole('ROLE_LECTURER')),
        ]);
    }
    
    public function timetablesDownload() :Response
    {
        return $this->render('downloads/timetables.html.twig', [
            'pageTitle' => 'Timetables downloads'
        ]);
    }
    
    public function notesDownload() :Response
    {
        return $this->render('downloads/notes.html.twig', [
            'pageTitle' => 'Notes downloads'
        ]);
    }
    
    /**
     * @return ObjectRepository
     * @deprecated
     */
    protected function getProfileRepository() :ObjectRepository
    {
        return $this->entityManager->getRepository(Profile::class);
    }
    
    /**
     * @return ObjectRepository
     * @deprecated
     */
    protected function getDepartmentRepository() :ObjectRepository
    {
        return $this->entityManager->getRepository(Department::class);
    }
    
    /**
     * @return ObjectRepository
     * @deprecated
     */
    protected function getCourseRepository() :ObjectRepository
    {
        return $this->entityManager->getRepository(Course::class);
    }
}

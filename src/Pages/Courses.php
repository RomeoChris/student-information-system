<?php

namespace App\Pages;


use App\Models\Course;
use App\Controller\AppController;
use Symfony\Component\HttpFoundation\Response;

class Courses extends AppController
{
    private $post;
    private $courseStorage;

    public function __construct()
    {
        $this->post = $this->getPost();
        $this->courseStorage = $this->getStorageManager()->getCourseStorage();
    }

    public function index() :Response
    {
        if (!$this->getAuthenticator()->isLoggedIn())
            return $this->redirectToRoute('index');

        return $this->renderTemplate('courses/index.html.twig', [
            'success' => $this->getSession()->flash('deleteSuccess'),
            'pageTitle' => 'All courses'
        ]);
    }

    public function new() :Response
    {
        if (!$this->getAuthenticator()->isLoggedIn())
            return $this->redirectToRoute('index');

        $errorList = [];

        $date = date('Y-m-d h:i:sa');
        $years = $this->post->get('years');
        $author = $this->getProfile()->getUsername();
        $courseName = $this->post->get('courseName');
        $department = $this->post->get('department');

        if ($this->getRequest()->isMethod('post'))
        {
            if (empty(($years || $courseName || $department)))
                $errorList[] = 'All fields are required';

            if (empty($errorList))
            {
                $course = new Course(0, $years, $author, $courseName, $department, $date);

                if ($this->courseStorage->save($course))
                {
                    $this->getSession()->set('newCourseSuccess', 'Course has been successfully added');
                    return $this->redirectToRoute('newCourse');
                }
                else
                    $errorList[] = 'Internal server error. Please try again later';
            }
        }

        return $this->renderTemplate('courses/new.html.twig', [
            'years' => $years,
            'errors' => $errorList,
            'success' => $this->getSession()->flash('newCourseSuccess'),
            'pageTitle' => 'Add new course',
            'courseName' => $courseName,
            'department' => $department
        ]);
    }

    public function delete($id = '') :Response
    {
        if (!$this->getAuthenticator()->isLoggedIn())
            return $this->redirectToRoute('index');

        $this->getAuthenticator()->requireAdmin();

        $course = $this->getCourse((int)$id ?? 0);

        if ($course->isSaved())
        {
            if ($this->courseStorage->delete($course))
            {
                $this->getSession()->set('deleteSuccess', 'Course deleted successfully');
                return $this->redirectToRoute('courses');
            }
            return $this->redirectToRoute('courses');
        }
        return $this->redirectToRoute('courses');
    }

    private function getCourse(int $id = 0) :Course
    {
        return $this->courseStorage->getById($id);
    }
}

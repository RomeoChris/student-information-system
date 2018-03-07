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

    public function view($id = '') :Response
    {
        if (!$this->getAuthenticator()->isLoggedIn())
            return $this->redirectToRoute('index');

        $course = $this->getCourse((int)$id ?? 0);

        if ($course->isSaved())
            return $this->renderTemplate('courses/view.html.twig', [
                'id' => $course->getIdentifier(),
                'years' => $course->getYears(),
                'author' => $course->getAuthor(),
                'pageTitle' => $course->getCourseName(),
                'courseName' =>$course->getCourseName(),
                'department' => $course->getDepartment()
            ]);
        return $this->redirectToRoute('courses');
    }

    public function edit($id = '') :Response
    {
        if (!$this->getAuthenticator()->isLoggedIn())
            return $this->redirectToRoute('index');

        $this->getAuthenticator()->requireAdmin();

        $errorList = [];

        $course = $this->getCourse((int)$id ?? 0);

        $years = $course->getYears();
        $department = $course->getDepartment();
        $courseName = $course->getCourseName();

        if ($course->isSaved())
        {
            if ($this->getRequest()->isMethod('post'))
            {
                $date = date('Y-m-d h:i:sa');
                $years = $this->post->get('years');
                $author = $this->getProfile()->getUsername();
                $courseName = $this->post->get('courseName');
                $department = $this->post->get('department');

                if (empty(($years || $courseName || $department)))
                    $errorList[] = 'All fields are required';

                if (empty($errorList))
                {
                    $course->setYears($years);
                    $course->setAuthor($author);
                    $course->setCourseName($courseName);
                    $course->setDepartment($department);
                    $course->setDateModified($date);

                    if ($this->courseStorage->save($course))
                    {
                        $this->getSession()->set('editCourseSuccess', 'Course has been successfully updated');
                        return $this->redirectToRoute('editCourse', ['id' => $course->getIdentifier()]);
                    }
                    else
                        $errorList[] = 'Internal server error. Please try again later';
                }
            }

            return $this->renderTemplate('courses/edit.html.twig', [
                'id' => $course->getIdentifier(),
                'years' => $years,
                'errors' => $errorList,
                'success' => $this->getSession()->flash('editCourseSuccess'),
                'pageTitle' => 'Edit course',
                'courseName' => $courseName,
                'department' => $department
            ]);
        }
        return $this->redirectToRoute('courses');
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

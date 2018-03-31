<?php

namespace App\Controller;


use App\Entity\Course;
use App\Entity\Department;
use DateTime;
use Symfony\Component\HttpFoundation\Response;

class CoursesController extends DefaultController
{
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

        $this->getAuthenticator()->requireAdmin();

        $years = (int)$this->getPost()->get('years', 0) ?? 0;
        $errorList = [];
        $courseName = $this->getPost()->get('courseName', '');
        $departmentId = (int)$this->getPost()->get('departmentId', 0) ?? 0;

        if ($this->getRequest()->isMethod('post'))
        {
            if (empty(($years || $courseName || $departmentId)))
                $errorList[] = 'All fields are required';

            if (empty($errorList))
            {
                $course = new Course();
                $course->setName($courseName);
                $course->setYears($years);
                $course->setDateCreated(new DateTime());
                $course->setDepartmentId($departmentId);

                $this->entityManager->persist($course);
                $this->entityManager->flush();

                $this->getSession()->set('newCourseSuccess', 'Course has been successfully added');
                return $this->redirectToRoute('newCourse');
            }
        }

        return $this->renderTemplate('courses/new.html.twig', [
            'years' => $years,
            'errors' => $errorList,
            'success' => $this->getSession()->flash('newCourseSuccess'),
            'pageTitle' => 'Add new course',
            'courseName' => $courseName,
            'departments' => $this->getDepartmentRepository()->findAll(),
        ]);
    }

    public function view(Course $course) :Response
    {
        if (!$this->getAuthenticator()->isLoggedIn())
            return $this->redirectToRoute('index');

        return $this->renderTemplate('courses/view.html.twig', [
            'id' => $course->getId(),
            'years' => $course->getYears(),
            'pageTitle' => $course->getName(),
            'courseName' => $course->getName(),
            'department' => $this->getDepartment($course->getDepartmentId())->getName()
        ]);
    }

    public function edit(Course $course) :Response
    {
        if (!$this->getAuthenticator()->isLoggedIn())
            return $this->redirectToRoute('index');

        $this->getAuthenticator()->requireAdmin();

        $errorList = [];

        $years = $course->getYears();
        $courseName = $course->getName();
        $departmentId = $course->getDepartmentId();
        $departmentName = $this->getDepartment($course->getDepartmentId())->getName();

        if ($this->getRequest()->isMethod('post'))
        {
            $years = $this->getPost()->get('years');
            $courseName = $this->getPost()->get('courseName');
            $departmentId = $this->getPost()->get('departmentId');

            if (empty(($years || $courseName || $departmentId)))
                $errorList[] = 'All fields are required';

            if (empty($errorList))
            {
                $course->setName($courseName);
                $course->setYears($years);
                $course->setDepartmentId($departmentId);
                $course->setDateModified(new DateTime());

                $this->entityManager->persist($course);
                $this->entityManager->flush();

                $this->getSession()->set('editCourseSuccess', 'Course has been successfully updated');
                return $this->redirectToRoute('editCourse', ['id' => $course->getId()]);
            }
        }

        return $this->renderTemplate('courses/edit.html.twig', [
            'id' => $course->getId(),
            'years' => $years,
            'errors' => $errorList,
            'success' => $this->getSession()->flash('editCourseSuccess'),
            'pageTitle' => 'Edit course',
            'courseName' => $courseName,
            'departments' => $this->getDepartmentRepository()->findAll(),
            'departmentId' => $departmentId,
            'departmentName' => $departmentName,
        ]);
    }

    public function delete(Course $course) :Response
    {
        if (!$this->getAuthenticator()->isLoggedIn())
            return $this->redirectToRoute('index');

        $this->getAuthenticator()->requireAdmin();

        $this->entityManager->remove($course);
        $this->entityManager->flush();

        $this->getSession()->set('deleteSuccess', 'Course deleted successfully');
        return $this->redirectToRoute('courses');
    }

    private function getDepartment(int $id = 0) :Department
    {
        /* @var $department Department */
        $department = $this->getDepartmentRepository()->find($id);
        return $department;
    }
}

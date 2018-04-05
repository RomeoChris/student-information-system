<?php

namespace App\Controller;


use App\Entity\Course;
use App\Entity\Department;
use App\Repository\DepartmentRepository;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class CoursesController extends DefaultController
{
    public function index() :Response
    {
        return $this->render('courses/index.html.twig', [
            'pageTitle' => 'All courses'
        ]);
    }

    public function new(
        Request $request,
        DepartmentRepository $repository,
        EntityManagerInterface $entityManager) :Response
    {
        $years = $request->request->getInt('years', 0);
        $errorList = [];
        $courseName = $request->request->get('courseName', '');
        $departmentId = $request->request->getInt('departmentId', 0);

        if ($request->isMethod('post'))
        {
            if (empty(($years || $courseName || $departmentId)))
                $errorList[] = 'All fields are required';

            if (empty($errorList))
            {
                $course = new Course();
                $course->setName($courseName);
                $course->setYears($years);
                $course->setDateCreated(new DateTime());
                $course->setDepartment($this->getDepartment($departmentId, $repository));

                $entityManager->persist($course);
                $entityManager->flush();

                $this->addFlash('success', 'Course has been successfully added');
                return $this->redirectToRoute('newCourse');
            }
        }

        return $this->render('courses/new.html.twig', [
            'years' => $years,
            'errors' => $errorList,
            'pageTitle' => 'Add new course',
            'courseName' => $courseName,
            'departments' => $repository->findAll(),
        ]);
    }

    public function view(Course $course) :Response
    {
        return $this->render('courses/view.html.twig', [
            'id' => $course->getId(),
            'years' => $course->getYears(),
            'pageTitle' => $course->getName(),
            'courseName' => $course->getName(),
            'department' => $course->getDepartment()->getName()
        ]);
    }

    public function edit(
        Course $course,
        Request $request,
        DepartmentRepository $repository,
        EntityManagerInterface $entityManager) :Response
    {
        $errorList = [];

        $years = $course->getYears();
        $courseName = $course->getName();
        $departmentId = $course->getDepartment()->getId();
        $departmentName = $course->getDepartment()->getName();

        if ($request->isMethod('post'))
        {
            $years = $request->request->getInt('years');
            $courseName = $request->request->get('courseName');
            $departmentId = $request->request->getInt('departmentId');

            if (empty(($years || $courseName || $departmentId)))
                $errorList[] = 'All fields are required';

            if (empty($errorList))
            {
                $course->setName($courseName);
                $course->setYears($years);
                $course->setDepartment($this->getDepartment($departmentId, $repository));
                $course->setDateModified(new DateTime());

                $entityManager->persist($course);
                $entityManager->flush();

                $this->addFlash('success', 'Course has been successfully updated');
                return $this->redirectToRoute('editCourse', ['id' => $course->getId()]);
            }
        }

        return $this->render('courses/edit.html.twig', [
            'id' => $course->getId(),
            'years' => $years,
            'errors' => $errorList,
            'pageTitle' => 'Edit course',
            'courseName' => $courseName,
            'departments' => $repository->findAll(),
            'departmentId' => $departmentId,
            'departmentName' => $departmentName,
        ]);
    }

    public function delete(Course $course, EntityManagerInterface $entityManager) :Response
    {
        $entityManager->remove($course);
        $entityManager->flush();

        $this->addFlash('success', 'Course deleted successfully');
        return $this->redirectToRoute('courses');
    }

    private function getDepartment(int $id = 0, DepartmentRepository $repository) :?Department
    {
        /* @var $department Department */
        $department = $repository->find($id);
        return $department;
    }
}

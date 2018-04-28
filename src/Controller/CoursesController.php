<?php

namespace App\Controller;


use App\Entity\Course;
use App\Form\CourseType;
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

    public function new(Request $request, EntityManagerInterface $entityManager) :Response
    {
        $course = new Course;
        $form = $this->createForm(CourseType::class, $course);
        $form->handleRequest($request);

        $params = [
            'form' => $form->createView(),
            'pageTitle' => 'Add new course',
            'formHeader' => 'Add new course',
        ];

        if (!$form->isSubmitted() || !$form->isValid())
            return $this->render('courses/new.html.twig', $params);

        $course->setDateCreated();
        $entityManager->persist($course);
        $entityManager->flush();
        $this->addFlash('success', 'Course has been successfully added');
        return $this->redirectToRoute('newCourse');
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
        EntityManagerInterface $entityManager) :Response
    {
        $form = $this->createForm(CourseType::class, $course);
        $form->handleRequest($request);

        $params = [
            'form' => $form->createView(),
            'pageTitle' => 'Update course',
            'formHeader' => 'Update course',
        ];

        if (!$form->isSubmitted() || !$form->isValid())
            return $this->render('courses/edit.html.twig', $params);

        $course->setDateModified();
        $entityManager->persist($course);
        $entityManager->flush();
        $this->addFlash('success', 'Course has been successfully updated');
        return $this->redirectToRoute('editCourse', ['id' => $course->getId()]);
    }

    public function delete(Course $course, EntityManagerInterface $entityManager) :Response
    {
        $entityManager->remove($course);
        $entityManager->flush();
        $this->addFlash('success', 'Course deleted successfully');
        return $this->redirectToRoute('courses');
    }
}

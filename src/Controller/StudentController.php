<?php

namespace App\Controller;

use App\Entity\Student;
use App\Form\StudentType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class StudentController extends AbstractController
{
    protected $em;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->em = $entityManager;
    }

    /**
     * @Route("/", name="student_list")
     */
    public function list()
    {
        $students = $this->em->getRepository(Student::class)->findAll();
        return $this->render('student/list.html.twig',[
            'students' => $students
        ]);
    }

    /**
     * @Route("/new_edit/{id}", defaults={"id" = null}, name="student_new_edit")
     */
    public function newEdit(Request $request, Student $student = null)
    {
        if ($student === null){
            $student = new Student();
        }

        $form = $this->createForm(StudentType::class, $student)->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
                $this->em->persist($student);
                $this->em->flush();
                $this->addFlash('success',"Success Add");
                return $this->redirectToRoute('student_list');
        }

        return $this->render('student/new_edit.html.twig', array(
            'student' => $student,
            'form' => $form->createView(),
        ));
    }

    /**
     * @Route("/delete/{id}", name="student_delete")
     */
    public function delete(Student $student)
    {
        $this->em->remove($student);
        $this->em->flush();
        $this->addFlash('success', 'Success Delete');
        return $this->redirectToRoute('student_list');
    }
}

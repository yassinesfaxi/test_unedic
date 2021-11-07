<?php

namespace App\Controller;

use App\Entity\Department;
use App\Entity\Student;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

class DepatmentController extends AbstractController
{
    protected $em;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->em = $entityManager;
    }

    /**
     * @Route("/api/get_students_department/{id}", name="get_students_department", methods={"GET"})
     */
    public function getStudents(Department $department, NormalizerInterface $normalizer)
    {
        $students = $this->em->getRepository(Student::class)->findBy([ 'department' => $department->getId() ]);

        $students = $normalizer->normalize($students,null, ['groups' => 'department']);

        return $this->json($students, 200);
    }
}

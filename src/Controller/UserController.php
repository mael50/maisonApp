<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\WorkSession;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class UserController extends AbstractController
{
    #[Route('/user', name: 'app_user_index')]
    public function index(EntityManagerInterface $em): Response
    {
        $employees = $em->getRepository(User::class)->findByRole('ROLE_EMPLOYEE');

        return $this->render('user/index.html.twig', [
            'employees' => $employees,
        ]);
    }

    #[Route('/user/{id}', name: 'app_user_show')]
    public function show(User $user, EntityManagerInterface $em): Response
    {
        $workSessions = $em->getRepository(WorkSession::class)->findBy(['user' => $user]);

        return $this->render('user/show.html.twig', [
            'user' => $user,
            'workSessions' => $workSessions,
        ]);
    }
}

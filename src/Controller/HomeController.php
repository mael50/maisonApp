<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\TaskRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\WorkSessionRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(TaskRepository $taskRepository, WorkSessionRepository $workSessionsRepository): Response
    {
        $tasks = $taskRepository->findBy(['assignedUser' => $this->getUser(), 'isDone' => false]);

        $workSessions = $workSessionsRepository->findBy(['user' => $this->getUser()]);

        return $this->render('home/index.html.twig', [
            'tasks' => $tasks,
            'worksessions' => $workSessions
        ]);
    }
}

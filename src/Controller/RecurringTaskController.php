<?php

namespace App\Controller;

use App\Entity\RecurringTasks;
use App\Form\RecurringTaskType;

use Doctrine\ORM\Mapping\Entity;
use App\Repository\TaskRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class RecurringTaskController extends AbstractController
{
    #[Route('/recurring/task', name: 'app_recurring_task')]
    public function index(TaskRepository $taskRepository, EntityManagerInterface $em): Response
    {
        $recurringTasks = $em->getRepository(RecurringTasks::class)->findAll(); 

        $form = $this->createForm(RecurringTaskType::class);

        if($form->isSubmitted() && $form->isValid()) {
            $formLoadData = $form->getData();
            $recurrency = $formLoadData['recurrency'];
            $task = $formLoadData['task'];
            $user = $formLoadData['user'];

            $recurringTask = new RecurringTasks();
            $recurringTask->setRecurrency($recurrency);
            $recurringTask->setTask($task);
            $recurringTask->setUser($user);

            $em->persist($recurringTask);
            $em->flush();

            return $this->redirectToRoute('app_recurring_task');
        }
        
        return $this->render('recurring_task/index.html.twig', [
            'recurring_tasks' => $recurringTasks,
            'form' => $form->createView()
        ]);
    }
}

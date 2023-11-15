<?php

namespace App\Controller;

use App\Entity\Task;
use App\Form\TaskType;
use App\Form\LoadTaskType;
use App\Entity\WorkSession;
use App\Repository\TaskRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class TaskController extends AbstractController
{
    #[Route('/task', name: 'app_task_index', methods: ['GET'])]
    public function index(TaskRepository $taskRepository): Response
    {
        if(!$this->isGranted('IS_AUTHENTICATED_FULLY')) {
            return $this->redirectToRoute('app_login');
        }
        if($this->isGranted('ROLE_EMPLOYER')) {
            $tasks = $taskRepository->findBy(['isDone' => false]);
        } else {
            $tasks = $taskRepository->findBy(['assignedUser' => $this->getUser(), 'isDone' => false]);
        }

        return $this->render('task/index.html.twig', [
            'tasks' => $tasks,
        ]);
    }

    #[Route('/task/new', name: 'app_task_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $task = new Task();
        $form = $this->createForm(TaskType::class, $task);
        $form->handleRequest($request);

        $formLoad = $this->createForm(LoadTaskType::class);
        $formLoad->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $task->setIsDone(false);
            $entityManager->persist($task);
            $entityManager->flush();

            return $this->redirectToRoute('app_task_index');
        }

        if ($formLoad->isSubmitted() && $formLoad->isValid()) {
            $formLoadData = $formLoad->getData();
            $tasks = $formLoadData['tasks'];
            $dueAt = $formLoadData['dueAt'];
            $assignedUser = $formLoadData['assignedUser'];

            foreach($tasks as $task) {
                $newTask = new Task();
                $newTask->setTitle($task->getTitle());
                $newTask->setDescription($task->getDescription());
                $newTask->setDueAt($dueAt);
                $newTask->setAssignedUser($assignedUser);
                $newTask->setIsSave(false);
                $newTask->setIsDone(false);
                $entityManager->persist($newTask);
                $this->addFlash('success', 'Tâche chargée avec succès');
            }
            $entityManager->flush();

            return $this->redirectToRoute('app_task_index');
        }

        $savedTasks = $entityManager->getRepository(Task::class)->findBy(['isSave' => true]);

        return $this->render('task/new.html.twig', [
            'task' => $task,
            'form' => $form,
            'formLoad' => $formLoad,
            'savedTasks' => $savedTasks
        ]);
    }

    #[Route('/task/{id}', name: 'app_task_show', methods: ['GET'])]
    public function show(Task $task): Response
    {
        return $this->render('task/show.html.twig', [
            'task' => $task,
        ]);
    }

    #[Route('/task/{id}/edit', name: 'app_task_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Task $task, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(TaskType::class, $task);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $task->setIsDone(false);
            $entityManager->flush();

            return $this->redirectToRoute('app_task_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('task/edit.html.twig', [
            'task' => $task,
            'form' => $form,
        ]);
    }

    #[Route('/task/{id}', name: 'app_task_delete', methods: ['POST'])]
    public function delete(Request $request, Task $task, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$task->getId(), $request->request->get('_token'))) {
            $entityManager->remove($task);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_task_index', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/task/{id}/{ws_id}/done', name: 'app_task_done', methods: ['GET'])]
    public function done(Request $request, Task $task, EntityManagerInterface $entityManager): Response
    {
        $workSession = $entityManager->getRepository(WorkSession::class)->find($request->get('ws_id'));
        $workSession->addTask($task);
        $task->setIsDone(true);
        $entityManager->flush();


        return $this->redirectToRoute('app_work_session_new');

    }

}

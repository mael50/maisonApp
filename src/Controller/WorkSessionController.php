<?php

namespace App\Controller;

use App\Entity\Task;
use App\Entity\WorkSession;
use App\Form\WorkSessionType;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\WorkSessionRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/work/session')]
class WorkSessionController extends AbstractController
{
    #[Route('/', name: 'app_work_session_index', methods: ['GET'])]
    public function index(WorkSessionRepository $workSessionRepository): Response
    {
        if($this->isGranted('ROLE_EMPLOYER')) {
            $work_sessions = $workSessionRepository->findAll();
        } else {
            $work_sessions = $workSessionRepository->findBy(['user' => $this->getUser()]);
        }

        return $this->render('work_session/index.html.twig', [
            'work_sessions' => $work_sessions
        ]);
    }

    #[Route('/new', name: 'app_work_session_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $workSession = new WorkSession();
        $workSession->setUser($this->getUser());
        $workSession->setStartDate(new \DateTimeImmutable());
        $workSession->setEndDate(new \DateTimeImmutable());
        $entityManager->persist($workSession);
        $entityManager->flush();

        // get all tasks to do (task with isDone to false and assign to current user)
        $tasks = $entityManager
            ->getRepository(Task::class)
            ->findBy([
                'isDone' => false,
                'assignedUser' => $this->getUser(),
            ]);

        return $this->render('work_session/new.html.twig', [
            'work_session' => $workSession,
            'tasks' => $tasks,
        ]);
    }

    #[Route('/end/{id}', name: 'app_work_session_end', methods: ['GET', 'POST'])]
    public function end(WorkSession $workSession, EntityManagerInterface $entityManager): Response
    {
        $workSession->setEndDate(new \DateTimeImmutable());
        $entityManager->flush();

        return $this->redirectToRoute('app_work_session_index', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/{id}', name: 'app_work_session_show', methods: ['GET'])]
    public function show(WorkSession $workSession): Response
    {
        return $this->render('work_session/show.html.twig', [
            'work_session' => $workSession,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_work_session_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, WorkSession $workSession, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(WorkSessionType::class, $workSession);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_work_session_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('work_session/edit.html.twig', [
            'work_session' => $workSession,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_work_session_delete', methods: ['POST'])]
    public function delete(Request $request, WorkSession $workSession, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$workSession->getId(), $request->request->get('_token'))) {
            $entityManager->remove($workSession);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_work_session_index', [], Response::HTTP_SEE_OTHER);
    }
}

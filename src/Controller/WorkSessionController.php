<?php

namespace App\Controller;

use App\Entity\Task;
use App\Entity\Comment;
use App\Form\CommentType;
use App\Entity\WorkSession;
use App\Form\WorkSessionType;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\WorkSessionRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Notifier\Notification\Notification;

#[Route('/work/session')]
class WorkSessionController extends AbstractController
{
    #[Route('/', name: 'app_work_session_index', methods: ['GET'])]
    public function index(WorkSessionRepository $workSessionRepository): Response
    {
        if(!$this->isGranted('IS_AUTHENTICATED_FULLY')) {
            return $this->redirectToRoute('app_login');
        }
        
        if($this->isGranted('ROLE_EMPLOYER')) {
            $work_sessions = $workSessionRepository->findAll();
        } else {
            $work_sessions = $workSessionRepository->findBy(['user' => $this->getUser()]);
        }

        $totalDuration = $workSessionRepository->getDurationByMonth($this->getUser(), new \DateTimeImmutable());

        return $this->render('work_session/index.html.twig', [
            'work_sessions' => $work_sessions,
            'totalDuration' => $totalDuration,
        ]);
    }

    #[Route('/new', name: 'app_work_session_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $tasks = $entityManager
        ->getRepository(Task::class)
        ->getTodoTasksForUser($this->getUser());


        $workSessionInProgress = $entityManager
        ->getRepository(WorkSession::class)
        ->findOneBy(['user' => $this->getUser(), 'endDate' => null]);

        if($workSessionInProgress) {
            $tasksDone = $workSessionInProgress->getTasks();
            return $this->render('work_session/new.html.twig', [
                'work_session' => $workSessionInProgress,
                'tasks' => $tasks,
                'tasksDone' => $tasksDone,
            ]);
        }
        $workSession = new WorkSession();
        $workSession->setUser($this->getUser());
        $workSession->setStartDate(new \DateTimeImmutable());
        $entityManager->persist($workSession);
        $entityManager->flush();


        $tasksDone = $workSession->getTasks();
        return $this->render('work_session/new.html.twig', [
            'work_session' => $workSession,
            'tasks' => $tasks,
            'tasksDone' => $tasksDone,
        ]);
    }

    #[Route('/end/{id}', name: 'app_work_session_end', methods: ['GET', 'POST'])]
    public function end(WorkSession $workSession, EntityManagerInterface $entityManager): Response
    {
        $workSession->setEndDate(new \DateTimeImmutable());
        $entityManager->flush();

        return $this->redirectToRoute('app_work_session_index', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/{id}', name: 'app_work_session_show', methods: ['GET', 'POST'])]
    public function show(Request $request, WorkSession $workSession, EntityManagerInterface $entityManager): Response
    {
        $comment = new Comment();
        $form = $this->createForm(CommentType::class, $comment);
        $form->handleRequest($request);

        $tasks = $workSession->getTasks();

        if ($form->isSubmitted() && $form->isValid()) {
            $comment->setAuthor($this->getUser());
            $comment->setWorkSession($workSession);
            $comment->setCreatedAt(new \DateTimeImmutable());
            $entityManager->persist($comment);
            $entityManager->flush();
            $this->addFlash('success', 'Commentaire ajouté !');
            return $this->redirectToRoute('app_work_session_show', ['id' => $workSession->getId()]);
        }
        
        return $this->render('work_session/show.html.twig', [
            'work_session' => $workSession,
            'form' => $form,
            'comments' => $workSession->getComments(),
            'tasks' => $tasks,
        ]);
    }

    #[Route('/{id}/delete', name: 'app_work_session_delete', methods: ['POST'])]
    public function delete(Request $request, WorkSession $workSession, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$workSession->getId(), $request->request->get('_token'))) {
            $entityManager->remove($workSession);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_work_session_index', [], Response::HTTP_SEE_OTHER);
    }
}


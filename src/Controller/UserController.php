<?php

namespace App\Controller;

use Dompdf\Dompdf;
use Dompdf\Options;
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

        $montWorkTime = $user->getMonthWorkTime();

        return $this->render('user/show.html.twig', [
            'employee' => $user,
            'workSessions' => $workSessions,
            'workTime' => $montWorkTime,
        ]);
    }

    #[Route('/user/{id}/invoice/{year}/{month}', name: 'app_user_invoice')]
    public function invoice(User $user, string $year, string $month, EntityManagerInterface $em): Response
    {
        $month = date('m', strtotime($month));

        $debut_mois = new \DateTime($year.'-'.$month.'-01');
        $fin_mois = new \DateTime($year.'-'.$month.'-'.date('t', strtotime($debut_mois->format('Y-m-d'))));

        // récupérer les sessions de travail qui ont une start_date entre le début et la fin du mois (par exemple toutes les sessions de travail du mois de janvier)
        $workSessions = $em->getRepository(WorkSession::class)->findByDate($debut_mois, $fin_mois, $user);

        $montWorkTime = $user->getWorkTimeFromMonth($month);
        $montWorkTimeH = $montWorkTime->format('H') + $montWorkTime->format('i') / 60;
        $totalSalary = 0;
        
        if ($user->getHourlyRate() !== null) {
            $totalSalary = $montWorkTimeH * $user->getHourlyRate();
        }


        $html = $this->renderView('user/invoice.html.twig', [
            'employee' => $user,
            'workSessions' => $workSessions,
            'totalDuration' => $montWorkTime,
            'totalSalary' => $totalSalary,
            'month' => $month,
            'year' => $year,
        ]);

        $options = new Options();

        $options->set('isHtml5ParserEnabled', true);
        $options->set('isPhpEnabled', true);

        $dompdf = new Dompdf($options);
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();

        $response = new Response($dompdf->output());
        $response->headers->set('Content-Type', 'application/pdf');
        $response->headers->set('Content-Disposition', 'inline; filename="facture_'.$month.'_'.$year.'_'.strtolower($user->getFirstname()).'_'.strtolower($user->getLastname()).'.pdf"');

        return $response;
    }
}

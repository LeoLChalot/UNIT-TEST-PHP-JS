<?php

namespace App\Controller;
use App\Controller\SecurityController;
use App\Repository\EmployeRepository;
use App\Repository\ChantierRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class DashboardController extends AbstractController
{
    #[Route('/dashboard', name: 'app_dashboard')]
    public function index(EmployeRepository $employeRepository, ChantierRepository $chantierRepository, SecurityController $security): Response
    {
        $user = $security->getUser(); 

 
        $nombreEmployes = $employeRepository->countEmployes();
        $chantiersTermines = $chantierRepository->countChantiersTermines();
        $chantiersEnCours = $chantierRepository->countChantiersEnCours();
        $chantiersPasCommences = $chantierRepository->countChantiersPasCommences();

        return $this->render('dashboard/index.html.twig', [
            'nombreEmployes' => $nombreEmployes,
            'chantiersTermines' => $chantiersTermines,
            'chantiersEnCours' => $chantiersEnCours,
            'chantiersPasCommences' => $chantiersPasCommences,
            'user' => $user,
        ]);
    }
}

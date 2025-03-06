<?php

namespace App\Controller;
use App\Controller\SecurityController;
use App\Repository\EmployeRepository;
use App\Repository\ChantierRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Validator\Constraints\Length;

final class DashboardController extends AbstractController
{
    #[Route('/admin/dashboard', name: 'app_dashboard')]
    public function index(EmployeRepository $employeRepository, ChantierRepository $chantierRepository, UserRepository $userRepository, SecurityController $security): Response
    {
        $user = $this->getUser(); 

 
        $nombreUser = count($userRepository->findAll());
        $nombreEmployes = count($employeRepository->findAll());
        $chantiersTermines = count($chantierRepository->findBy(['statut' => 'Terminé']));
        $chantiersEnCours = count($chantierRepository->findBy(['statut' => 'En cours']));
        $chantiersPasCommences =  count($chantierRepository->findBy(['statut' => 'A venir']));


        return $this->render('dashboard/index.html.twig', [
            'nombreUser' => $nombreUser,
            'nombreEmployes' => $nombreEmployes,
            'chantiersTermines' => $chantiersTermines,
            'chantiersEnCours' => $chantiersEnCours,
            'chantiersPasCommences' => $chantiersPasCommences,
            'user' => $user,
        ]);
    }
}

<?php

namespace App\Controller;

use App\Entity\Chantier;
use App\Form\ChantierType;
use App\Repository\ChantierRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/admin/chantier')]
final class ChantierController extends AbstractController
{
    #[Route(name: 'app_chantier_index', methods: ['GET'])]
    public function index(ChantierRepository $chantierRepository): Response
    {
        return $this->render('chantier/index.html.twig', [
            'chantiers' => $chantierRepository->findAll(),
        ]);
    }

    #[Route('/termine', name:'app_chantier_termine', methods: ['GET'])]
    public function chantier_fini(ChantierRepository $chantierRepository): Response
    {

        return $this->render('chantier/index.html.twig', [
            'chantiers' => $chantierRepository->findBy(['statut' => 'Terminé']),
        ]);
    }

    #[Route('/encours', name: 'app_chantier_en_cours', methods: ['GET'])]
    public function chantier_en_cours(ChantierRepository $chantierRepository): Response
    {   
        $chantiers = $chantierRepository->findBy(['statut' => 'En cours']);
        return $this->render('chantier/index.html.twig', [
            'chantiers' => $chantiers,
        ]);
    }

    #[Route('/avenir', name: 'app_chantier_a_venir', methods: ['GET'])]
    public function chantier_a_venir(ChantierRepository $chantierRepository): Response
    {
        $chantiers = $chantierRepository->findBy(['statut' => 'A venir']);
        return $this->render('chantier/index.html.twig', [
            'chantiers' => $chantiers,
        ]);
    }

    #[Route('/new', name: 'app_chantier_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $chantier = new Chantier();
        $form = $this->createForm(ChantierType::class, $chantier);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($chantier);

            if ($chantier->getDateDeFin() <= $chantier->getDateDeDebut()) {
                $this->addFlash('error', 'La date de fin doit être supérieure à la date de début.');
                return $this->redirectToRoute('app_chantier_new');
            }

            if ($chantier->getDateDeDebut() < new \DateTime()) {
                $this->addFlash('error', 'La date de début doit être supérieure ou égale à la date actuelle.');
                return $this->redirectToRoute('app_chantier_new');
            }

            $chantier->setDateTacheSuivante($chantier->getDateDeDebut());
            $entityManager->flush();

            return $this->redirectToRoute('app_chantier_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('chantier/new.html.twig', [
            'chantier' => $chantier,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_chantier_show', methods: ['GET'])]
    public function show(Chantier $chantier): Response
    {
        return $this->render('chantier/show.html.twig', [
            'chantier' => $chantier,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_chantier_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Chantier $chantier, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(ChantierType::class, $chantier);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_chantier_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('chantier/edit.html.twig', [
            'chantier' => $chantier,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_chantier_delete', methods: ['POST'])]
    public function delete(Request $request, Chantier $chantier, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$chantier->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($chantier);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_chantier_index', [], Response::HTTP_SEE_OTHER);
    }

}

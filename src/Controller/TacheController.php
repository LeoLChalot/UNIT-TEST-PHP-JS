<?php

namespace App\Controller;

use App\Entity\Tache;
use App\Form\TacheType;
use App\Repository\TacheRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/tache')]
final class TacheController extends AbstractController
{
    #[Route(name: 'app_tache_index', methods: ['GET'])]
    public function index(TacheRepository $tacheRepository): Response
    {

        return $this->render('tache/index.html.twig', [
            'taches' => $tacheRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_tache_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $tache = new Tache();
        $form = $this->createForm(TacheType::class, $tache);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($tache);

            $chantier = $tache->getChantier();



            $dureeEnJours = (float) $tache->getDuree();
            $jours = (int) $dureeEnJours; // Partie entière (jours)
            $heures = (int) (($dureeEnJours - $jours) * 24); // Partie décimale en heures

            // Crée un DateInterval avec la durée
            $dureeInterval = new \DateInterval("P{$jours}DT{$heures}H");

            // Calcule la date de fin en ajoutant la durée à la date de début du chantier
            $startDate = $tache->getChantier()->getDateTacheSuivante();
            dump($startDate, $dureeInterval);
            $startDate = new \DateTime($startDate->format('Y-m-d'));
            $endDate = clone $startDate;
            $endDate->add($dureeInterval);


            // Définit la date de fin sur la tâche
            $tache->setDateDeFin($endDate);
            $chantier->setDateTacheSuivante($endDate);

            // Comparer la date de fin de la tâche avec celle du chantier
            if ($tache->getDateDeFin() > $chantier->getDateDeFin()) {
                // Ajouter un message flash pour alerter l'utilisateur
                $this->addFlash('warning', 'Attention : La date de fin de la tâche dépasse la date de fin prévue du chantier.');
            }

            // Message de succès avec la durée formatée
            $dureeFormatee = sprintf('%d jours et %d heures', $jours, $heures);
            $this->addFlash('success', 'Tâche créée pour le chantier: ' . $chantier->getNom() . ' du ' . $startDate->format('d/m/Y') . ' pour une durée de ' . $dureeFormatee . '.');

            // Enregistre la tâche dans la base de données
            $entityManager->persist($tache);
            $entityManager->flush();

            return $this->redirectToRoute('app_tache_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('tache/new.html.twig', [
            'tache' => $tache,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_tache_show', methods: ['GET'])]
    public function show(Tache $tache): Response
    {
        $duree = $tache->getDuree();
        $jours = (int) $duree; // Partie entière (jours)
        $heures = (int) (($duree - $jours) * 24); // Partie décimale en heures

        $startDate = new \DateTime($tache->getChantier()->getDateDeDebut()->format('Y-m-d'));
        $fin_prevue = $startDate->add(new \DateInterval("P{$jours}DT{$heures}H"));


        return $this->render('tache/show.html.twig', [
            'tache' => $tache,
            'dureeFormatee' => sprintf('%d j et %d h', $jours, $heures),
            'fin_prevue' => $tache->getDateDeFin()->format('d/m/Y'),
        ]);
    }

    #[Route('/{id}/edit', name: 'app_tache_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Tache $tache, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(TacheType::class, $tache);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_tache_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('tache/edit.html.twig', [
            'tache' => $tache,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_tache_delete', methods: ['POST'])]
    public function delete(Request $request, Tache $tache, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete' . $tache->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($tache);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_tache_index', [], Response::HTTP_SEE_OTHER);
    }
}

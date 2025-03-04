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

#[Route('/chantier')]
final class ChantierController extends AbstractController
{
    #[Route(name: 'app_chantier_index', methods: ['GET'])]
    public function index(ChantierRepository $chantierRepository): Response
    {
        return $this->render('chantier/index.html.twig', [
            'chantiers' => $chantierRepository->findAll(),
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

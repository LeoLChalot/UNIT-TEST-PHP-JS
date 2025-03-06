<?php

namespace App\Controller;

use App\Entity\Assignation;
use App\Entity\Employe;
use App\Entity\Tache;
use App\Form\TacheType;
use App\Repository\AssignationRepository;
use App\Repository\TacheRepository;
use DateInterval;
use DateTime;
use Doctrine\DBAL\Types\DateTimeType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Validator\Constraints\Date;

#[Route('/admin/tache')]
final class TacheController extends AbstractController
{
    private $assignationRepository;

    public function __construct(AssignationRepository $assignmentRepository)
    {
        $this->assignationRepository = $assignmentRepository;
    }

    /**
     * ## Vérifie si un employé est disponible pour une période donnée.
     *
     * Cette fonction vérifie si un employé est disponible entre deux dates spécifiques
     * en s'assurant qu'il n'a pas d'autres tâches assignées qui se chevauchent avec cette période.
     *
     * @param int $employeeId L'identifiant de l'employé à vérifier.
     * @param DateTime $date_de_debut La date de début de la période à vérifier.
     * @param DateTime $date_de_fin La date de fin de la période à vérifier.
     * @return bool Retourne true si l'employé est disponible, sinon false.
     */
    private function estDisponible(Employe $employe, DateTime $date_de_debut_nouvelle_tache, DateTime $date_de_fin_nouvelle_tache): bool
    {
        $assignations = $employe->getAssignations();
        foreach ($assignations as $assignation) // Vérifier si les dates se chevauchent
        {
            if (
                ($date_de_debut_nouvelle_tache >= $assignation->getDateDeDebut() && $date_de_debut_nouvelle_tache <= $assignation->getDateDeFin()) ||
                ($date_de_fin_nouvelle_tache >= $assignation->getDateDeDebut() && $date_de_fin_nouvelle_tache <= $assignation->getDateDeFin()) ||
                ($date_de_debut_nouvelle_tache <= $assignation->getDateDeDebut() && $date_de_fin_nouvelle_tache >= $assignation->getDateDeFin())
            ) {
                return false; // L'employé est déjà occupé pendant cette période
            }
        }
        return true; // L'employé est disponible
    }


    /**
     * Calcule la durée d'une tâche en jours et heures.
     *
     * @param Tache $tache L'objet Tache dont on veut calculer la durée.
     * @return DateInterval Un objet \DateInterval représentant la durée.
     */
    private function getDureeTache(Tache $tache): DateInterval
    {
        $dureeEnJours = (float) $tache->getDuree();
        $jours = (int) $dureeEnJours; // Partie entière (jours)
        $heures = (int) (($dureeEnJours - $jours) * 24); // Partie décimale en heures
        return new \DateInterval("P{$jours}DT{$heures}H");
    }

    /**
     * ## Calcule la date de début, de fin ainsi que la durée d'une tâche.
     *
     * @param Tache $tache L'objet Tache pour lequel calculer les dates et la durée.
     * 
     * @return array Un tableau associatif contenant :
     *               - 'date_de_debut' (\DateTime) : La date de début de la tâche.
     *               - 'date_de_fin' (\DateTime) : La date de fin de la tâche.
     *               - 'duree' (\DateInterval) : La durée de la tâche.
     */
    private function getDebutFinTache(Tache $tache): array
    {
        $dureeInterval = $this->getDureeTache($tache);
        $date_tache_suivante = $tache->getChantier()->getDateTacheSuivante();
        $date_de_debut = new \DateTime($date_tache_suivante->format('Y-m-d'));
        $date_de_fin = clone $date_de_debut;
        $date_de_fin->add($dureeInterval);
        return [
            'date_de_debut' => $date_de_debut,
            'date_de_fin' => $date_de_fin,
        ];
    }

    /**
     * ## Définit les dates de début et de fin pour une tâche donnée.
     *
     * Cette méthode récupère les dates de début et de fin d'une tâche
     * en utilisant la méthode getDebutFinDureeTache() et les assigne
     * à la tâche passée en paramètre.
     *
     * @param Tache $tache L'objet Tache pour lequel les dates doivent être définies.
     * @return bool Retourne toujours true après avoir défini les dates.
     */
    private function setDateDebutFinTache(Tache $tache, array $debut_fin): bool
    {
        $tache->setDateDeDebut($debut_fin['date_de_debut']);
        $tache->setDateDeFin($debut_fin['date_de_fin']);
        return true;
    }

    /**
     * Crée une nouvelle assignation pour une tâche donnée à un employé spécifique avec des dates de début et de fin.
     *
     * @param Tache $tache L'objet Tache à assigner.
     * @param Employe $employe L'objet Employe à qui la tâche est assignée.
     * @param DateTime $date_de_debut La date de début de l'assignation.
     * @param DateTime $date_de_fin La date de fin de l'assignation.
     * @return Assignation L'objet Assignation créé.
     */
    private function setAssiganationTache(Tache $tache, Employe $employe, DateTime $date_de_debut, DateTime $date_de_fin): Assignation
    {
        $assignation = new Assignation();
        $assignation->setEmploye($employe);
        $assignation->setTache($tache);
        $assignation->setDateDeDebut($date_de_debut);
        $assignation->setDateDeFin($date_de_fin);
        return $assignation;
    }

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
            // Récupérer le chantier associé
            $chantier = $tache->getChantier();

            // Récupérer les dates de début et de fin de la tâche
            $debut_fin_tache = $this->getDebutFinTache($tache);
            $date_de_debut = $debut_fin_tache['date_de_debut'];
            $date_de_fin = $debut_fin_tache['date_de_fin'];

            // Définir les dates de début et de fin de la tâche
            $this->setDateDebutFinTache($tache, $debut_fin_tache);

            // Vérifier si la date de fin de la tâche dépasse la date de fin prévue du chantier
            if ($tache->getDateDeFin() > $chantier->getDateDeFin()) {
                $this->addFlash(
                    'warning',
                    'Attention : La date de fin de la tâche dépasse la date de fin prévue du chantier.'
                );
            }

            // Récupérer les employés assignés à la tâche
            $employes = $tache->getEmployes();
            $tousEmployesDisponibles = true;
            $employes_indisponibles = [];

            // Vérifier la disponibilité de chaque employé
            foreach ($employes as $employe) {
                // Vérifier si l'employé est déjà assigné à cette tâche
                $assignationExistante = $entityManager->getRepository(Assignation::class)->findOneBy([
                    'tache' => $tache,
                    'employe' => $employe,
                ]);

                if ($assignationExistante) {
                    $tousEmployesDisponibles = false;
                    continue;
                }

                // Vérifier si l'employé est disponible pour la période
                if (!$this->estDisponible($employe, $date_de_debut, $date_de_fin)) {
                    $employes_indisponibles[] = $employe->getNom() . ' ' . $employe->getPrenom();
                    $tousEmployesDisponibles = false;
                }
            }

            // Si tous les employés sont disponibles, persister la tâche et les assignations
            if ($tousEmployesDisponibles) {
                // Persister la tâche
                $entityManager->persist($tache);

                // Mettre à jour la date de la prochaine tâche du chantier
                $chantier->setDateTacheSuivante($tache->getDateDeFin());

                // Persister les assignations
                foreach ($employes as $employe) {
                    $assignation = $this->setAssiganationTache($tache, $employe, $date_de_debut, $date_de_fin);
                    $entityManager->persist($assignation);
                }

                // Flush pour enregistrer en base de données
                $entityManager->flush();

                // Formatage de la durée de la tâche
                $duree_tache = $this->getDureeTache($tache);
                $dureeFormatee = $duree_tache->format('%d jours et %h heures');

                // Message de succès
                $this->addFlash(
                    'success',
                    'Tâche créée pour le chantier: '
                        . $chantier->getNom()
                        . ' du '
                        . $date_de_debut->format('d/m/Y')
                        . ' pour une durée de '
                        . $dureeFormatee
                        . '.'
                );
                return $this->redirectToRoute('app_tache_index', [], Response::HTTP_SEE_OTHER);
            } else {
                // Si un employé n'est pas disponible, afficher un message d'erreur
                $errorMessage = 'Les employés suivants ne sont pas disponibles pour la période de la tâche : '
                    . implode(', ', $employes_indisponibles);
                $this->addFlash('error', $errorMessage);

                // Rediriger vers la page du formulaire pour afficher les messages flash
                return $this->redirectToRoute('app_tache_new');
            }
        }

        // Afficher le formulaire
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

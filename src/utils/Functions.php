<?php

namespace App\utils;
use App\Entity\Assignation;
use App\Entity\Employe;
use App\Entity\Tache;
use DateInterval;
use DateTime;

class Functions
{

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
    public static function estDisponibleEmploye(Employe $employe, DateTime $date_de_debut_nouvelle_tache, DateTime $date_de_fin_nouvelle_tache): bool
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
    public static function getDureeTache(Tache $tache): DateInterval
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
    public static function getDebutFinTache(Tache $tache): array
    {
        $dureeInterval = self::getDureeTache($tache);
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
    public static function setDateDebutFinTache(Tache $tache, array $debut_fin): bool
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
    public static function setAssiganationTache(Tache $tache, Employe $employe, DateTime $date_de_debut, DateTime $date_de_fin): Assignation
    {
        $assignation = new Assignation();
        $assignation->setEmploye($employe);
        $assignation->setTache($tache);
        $assignation->setDateDeDebut($date_de_debut);
        $assignation->setDateDeFin($date_de_fin);
        return $assignation;
    }
}

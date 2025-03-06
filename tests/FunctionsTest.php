<?php

namespace App\Tests;

use App\Entity\Assignation;
use App\Entity\Chantier;
use App\Entity\Employe;
use App\Entity\Tache;
use App\utils\Functions;
use DateInterval;
use DateTime;
use PHPUnit\Framework\TestCase;

class FunctionsTest extends TestCase
{
    /**
     * Teste la disponibilité d'un employé pour une nouvelle tâche.
     *
     * Ce test vérifie si un employé est disponible pour une nouvelle tâche en fonction de ses assignations existantes.
     * 
     * Scénarios testés :
     * 1. L'employé a une assignation du 1er mars 2025 au 5 mars 2025.
     *    - On vérifie la disponibilité de l'employé pour une nouvelle tâche du 6 mars 2025 au 10 mars 2025.
     *      La méthode `estDisponibleEmploye` doit retourner `true` car il n'y a pas de chevauchement.
     * 2. On vérifie la disponibilité de l'employé pour une nouvelle tâche du 4 mars 2025 au 7 mars 2025.
     *      La méthode `estDisponibleEmploye` doit retourner `false` car il y a un chevauchement avec l'assignation existante.
     */
    public function testEstDisponibleEmploye()
    {
        $employe = new Employe();
        $dateDebut1 = new DateTime('2025-03-01');
        $dateFin1 = new DateTime('2025-03-05');
        $assignation1 = new Assignation();
        $assignation1->setDateDeDebut($dateDebut1);
        $assignation1->setDateDeFin($dateFin1);
        $employe->addAssignation($assignation1);

        $dateDebutNouvelleTache = new DateTime('2025-03-06');
        $dateFinNouvelleTache = new DateTime('2025-03-10');

        $this->assertTrue(Functions::estDisponibleEmploye($employe, $dateDebutNouvelleTache, $dateFinNouvelleTache));

        $dateDebutNouvelleTache = new DateTime('2025-03-04');
        $dateFinNouvelleTache = new DateTime('2025-03-07');

        $this->assertFalse(Functions::estDisponibleEmploye($employe, $dateDebutNouvelleTache, $dateFinNouvelleTache));
    }

    /**
     * Teste la méthode getDureeTache de la classe Functions.
     *
     * Cette méthode vérifie que la durée d'une tâche est correctement calculée
     * et retournée sous forme d'un objet DateInterval.
     *
     * Scénario de test :
     * - Crée une nouvelle tâche.
     * - Définit la durée de la tâche à 2.5 jours (2 jours et 12 heures).
     * - Appelle la méthode getDureeTache pour obtenir la durée de la tâche.
     * - Vérifie que le résultat est une instance de DateInterval.
     * - Vérifie que le nombre de jours est égal à 2.
     * - Vérifie que le nombre d'heures est égal à 12.
     */
    public function testGetDureeTache()
    {
        $tache = new Tache();
        $tache->setDuree(2.5); // 2 jours et 12 heures

        $duree = Functions::getDureeTache($tache);

        $this->assertInstanceOf(DateInterval::class, $duree);
        $this->assertEquals(2, $duree->d);
        $this->assertEquals(12, $duree->h);
    }

    /**
     * Teste la méthode getDebutFinTache de la classe Functions.
     *
     * Ce test vérifie que la méthode getDebutFinTache retourne correctement
     * les dates de début et de fin d'une tâche en fonction de sa durée et
     * de la date de la tâche suivante du chantier associé.
     *
     * Scénario testé :
     * - Création d'un mock de Chantier avec une date de tâche suivante fixée au 1er mars 2025.
     * - Création d'une tâche avec une durée de 2,5 jours (2 jours et 12 heures).
     * - Association de la tâche au chantier mocké.
     * - Appel de la méthode getDebutFinTache et vérification des résultats.
     *
     * Assertions :
     * - Le tableau retourné par getDebutFinTache contient les clés 'date_de_debut' et 'date_de_fin'.
     * - La date de début est égale au 1er mars 2025.
     * - La date de fin est égale au 3 mars 2025 à 12:00:00.
     */
    public function testGetDebutFinTache()
    {
        $chantier = $this->getMockBuilder(Chantier::class)
                         ->disableOriginalConstructor()
                         ->getMock();
        $chantier->method('getDateTacheSuivante')->willReturn(new DateTime('2025-03-01'));

        $tache = new Tache();
        $tache->setDuree(2.5); // 2 jours et 12 heures
        $tache->setChantier($chantier instanceof Chantier ? $chantier : null);

        $result = Functions::getDebutFinTache($tache);

        $this->assertArrayHasKey('date_de_debut', $result);
        $this->assertArrayHasKey('date_de_fin', $result);

        $this->assertEquals(new DateTime('2025-03-01'), $result['date_de_debut']);
        $this->assertEquals(new DateTime('2025-03-03 12:00:00'), $result['date_de_fin']);
    }

    /**
     * Teste la méthode setDateDebutFinTache de la classe Functions.
     *
     * Ce test vérifie que les dates de début et de fin d'une tâche sont correctement définies.
     *
     * Scénario de test :
     * 1. Crée une nouvelle instance de Tache.
     * 2. Définit un tableau associatif contenant les dates de début et de fin.
     * 3. Appelle la méthode setDateDebutFinTache avec l'instance de Tache et le tableau de dates.
     * 4. Vérifie que la méthode retourne true.
     * 5. Vérifie que la date de début de la tâche est correctement définie.
     * 6. Vérifie que la date de fin de la tâche est correctement définie.
     *
     * @return void
     */
    public function testSetDateDebutFinTache()
    {
        $tache = new Tache();
        $debutFin = [
            'date_de_debut' => new DateTime('2025-03-01'),
            'date_de_fin' => new DateTime('2025-03-03 12:00:00'),
        ];

        $result = Functions::setDateDebutFinTache($tache, $debutFin);

        $this->assertTrue($result);
        $this->assertEquals(new DateTime('2025-03-01'), $tache->getDateDeDebut());
        $this->assertEquals(new DateTime('2025-03-03 12:00:00'), $tache->getDateDeFin());
    }

    /**
     * Teste la méthode setAssignationTache de la classe Functions.
     *
     * Ce test vérifie que la méthode setAssignationTache crée correctement une instance de la classe Assignation
     * avec les propriétés appropriées pour une tâche, un employé, une date de début et une date de fin.
     *
     * Scénario de test :
     * - Crée une nouvelle instance de Tache.
     * - Crée une nouvelle instance de Employe.
     * - Définit une date de début et une date de fin.
     * - Appelle la méthode setAssignationTache avec ces paramètres.
     * - Vérifie que l'objet retourné est une instance de la classe Assignation.
     * - Vérifie que les propriétés de l'objet Assignation correspondent aux valeurs passées en paramètres.
     *
     * @return void
     */
    public function testSetAssignationTache()
    {
        $tache = new Tache();
        $employe = new Employe();
        $dateDebut = new DateTime('2025-03-01');
        $dateFin = new DateTime('2025-03-03');

        $assignation = Functions::setAssiganationTache($tache, $employe, $dateDebut, $dateFin);

        $this->assertInstanceOf(Assignation::class, $assignation);
        $this->assertEquals($tache, $assignation->getTache());
        $this->assertEquals($employe, $assignation->getEmploye());
        $this->assertEquals($dateDebut, $assignation->getDateDeDebut());
        $this->assertEquals($dateFin, $assignation->getDateDeFin());
    }
}
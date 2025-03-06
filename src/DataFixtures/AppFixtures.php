<?php

namespace App\DataFixtures;

use App\Entity\Chantier;
use App\Entity\Client;
use App\Entity\Employe;
use App\Entity\Metier;
use App\Entity\User;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{

    private UserPasswordHasherInterface $passwordHasher;

    public function __construct(UserPasswordHasherInterface $passwordHasher)
    {
        $this->passwordHasher = $passwordHasher;
    }
    public function load(ObjectManager $manager): void
    {

        // ********************************************** //
        // *********** Création des utilisateurs ******** //
        // ********************************************** //
        $user1 = new User;
        $user1->setNom('super');
        $user1->setPrenom('admin');
        $user1->setTelephone('061234567890');
        $user1->setEmail('superadmin@admin.fr');
        $hashedPassword = $this->passwordHasher->hashPassword($user1, 'superadmin');
        $user1->setPassword($hashedPassword);
        $user1->setRoles(['ROLE_SUPER_ADMIN']);
        $manager->persist($user1);

        $user2 = new User;
        $user2->setNom('admin');
        $user2->setPrenom('admin');
        $user2->setTelephone('061234567891');
        $user2->setEmail('admin@admin.fr');
        $hashedPassword = $this->passwordHasher->hashPassword($user2, 'admin');
        $user2->setPassword($hashedPassword);
        $user2->setRoles(['ROLE_ADMIN']);
        $manager->persist($user2);


        // ********************************************** //
        // *********** Création des métiers ************* //
        // ********************************************** //
        $metier1 = new Metier();
        $metier1->setLabel('Plombier');
        $manager->persist($metier1);

        $metier2 = new Metier();
        $metier2->setLabel('Électricien');
        $manager->persist($metier2);

        $metier3 = new Metier();
        $metier3->setLabel('Maçon');
        $manager->persist($metier3);

        $metier4 = new Metier();
        $metier4->setLabel('Carreleur');
        $manager->persist($metier4);

        $metier5 = new Metier();
        $metier5->setLabel('Peintre');
        $manager->persist($metier5);

        $metier6 = new Metier();
        $metier6->setLabel('Menuisier');
        $manager->persist($metier6);

        $manager->flush();

        // ********************************************** //
        // *********** Création des employés ************ //
        // ********************************************** //
        $employe1 = new Employe();
        $employe1->setNom('Dupont');
        $employe1->setPrenom('Jean');
        $employe1->setTelephone('061234567892');
        $employe1->setMetier($metier1);
        $employe1->setEstChefDeChantier(true);
        $employe1->setDisponible(true);
        $manager->persist($employe1);

        $employe2 = new Employe();
        $employe2->setNom('Martin');
        $employe2->setPrenom('Marie');
        $employe2->setTelephone('061234567893');
        $employe2->setMetier($metier2);
        $employe2->setEstChefDeChantier(true);
        $employe2->setDisponible(true);
        $manager->persist($employe2);

        $employe3 = new Employe();
        $employe3->setNom('Durand');
        $employe3->setPrenom('Paul');
        $employe3->setTelephone('061234567894');
        $employe3->setMetier($metier3);
        $employe3->setEstChefDeChantier(true);
        $employe3->setDisponible(true);
        $manager->persist($employe3);

        $employe4 = new Employe();
        $employe4->setNom('Lefevre');
        $employe4->setPrenom('Julie');
        $employe4->setTelephone('061234567895');
        $employe4->setMetier($metier4);
        $employe4->setEstChefDeChantier(true);
        $employe4->setDisponible(true);
        $manager->persist($employe4);

        $employe5 = new Employe();
        $employe5->setNom('Leroy');
        $employe5->setPrenom('Pierre');
        $employe5->setTelephone('061234567896');
        $employe5->setMetier($metier5);
        $employe5->setEstChefDeChantier(false);
        $employe5->setDisponible(true);
        $manager->persist($employe5);

        $employe6 = new Employe();
        $employe6->setNom('Roux');
        $employe6->setPrenom('Sophie');
        $employe6->setTelephone('061234567897');
        $employe6->setMetier($metier6);
        $employe6->setEstChefDeChantier(true);
        $employe6->setDisponible(true);
        $manager->persist($employe6);
        $employe7 = new Employe();
        $employe7->setNom('Petit');
        $employe7->setPrenom('Luc');
        $employe7->setTelephone('061234567801');
        $employe7->setMetier($metier1);
        $employe7->setEstChefDeChantier(false);
        $employe7->setDisponible(true);
        $manager->persist($employe7);

        $employe8 = new Employe();
        $employe8->setNom('Garcia');
        $employe8->setPrenom('Laura');
        $employe8->setTelephone('061234567802');
        $employe8->setMetier($metier2);
        $employe8->setEstChefDeChantier(false);
        $employe8->setDisponible(true);
        $manager->persist($employe8);

        $employe9 = new Employe();
        $employe9->setNom('Blanc');
        $employe9->setPrenom('Alice');
        $employe9->setTelephone('061234567803');
        $employe9->setMetier($metier3);
        $employe9->setEstChefDeChantier(false);
        $employe9->setDisponible(true);
        $manager->persist($employe9);

        $employe10 = new Employe();
        $employe10->setNom('Noir');
        $employe10->setPrenom('Jacques');
        $employe10->setTelephone('061234567804');
        $employe10->setMetier($metier4);
        $employe10->setEstChefDeChantier(false);
        $employe10->setDisponible(true);
        $manager->persist($employe10);

        $manager->flush();

        // ********************************************** //
        // *********** Création des clients ************* //
        // ********************************************** //
        $client1 = new Client();
        $client1->setNom('Bernard');
        $client1->setTelephone('061234567898');
        $manager->persist($client1);

        $client2 = new Client();
        $client2->setNom('Dubois');
        $client2->setTelephone('061234567899');
        $manager->persist($client2);

        $client3 = new Client();
        $client3->setNom('Moreau');
        $client3->setTelephone('061234567800');
        $manager->persist($client3);

        $client4 = new Client();
        $client4->setNom('Lemoine');
        $client4->setTelephone('061234567803');
        $manager->persist($client4);

        $client5 = new Client();
        $client5->setNom('Renaud');
        $client5->setTelephone('061234567804');
        $manager->persist($client5);

        $client6 = new Client();
        $client6->setNom('Durant');
        $client6->setTelephone('061234567805');
        $manager->persist($client6);

        $client7 = new Client();
        $client7->setNom('Leroux');
        $client7->setTelephone('061234567806');
        $manager->persist($client7);

        $manager->flush();


        // ********************************************** //
        // *********** Création des chantiers *********** //
        // ********************************************** //
        $chantier1 = new Chantier();
        $chantier1->setNom('Rénovation Maison');
        $chantier1->setNumeroDeLaVoie('123');
        $chantier1->setTypeDeVoie('Rue');
        $chantier1->setLibelleDeLaVoie('de la Paix');
        $chantier1->setCodePostal('75000');
        $chantier1->setVille('Paris');
        $chantier1->setDateDeDebut(new \DateTime('2025-03-01'));
        $chantier1->setDateDeFin(new \DateTime('2025-03-05'));
        $chantier1->setStatut('Terminé');
        $chantier1->setDateTacheSuivante($chantier1->getDateDeDebut());
        $chantier1->setClient($client1);
        $chantier1->setChefDeChantier($employe1);
        $manager->persist($chantier1);

        $chantier2 = new Chantier();
        $chantier2->setNom('Construction Immeuble');
        $chantier2->setNumeroDeLaVoie('456');
        $chantier2->setTypeDeVoie('Avenue');
        $chantier2->setLibelleDeLaVoie('de la République');
        $chantier2->setCodePostal('69000');
        $chantier2->setVille('Lyon');
        $chantier2->setDateDeDebut(new \DateTime('2025-03-06'));
        $chantier2->setDateDeFin(new \DateTime('2025-03-10'));
        $chantier2->setStatut('En cours');
        $chantier2->setDateTacheSuivante($chantier2->getDateDeDebut());
        $chantier2->setClient($client2);
        $chantier2->setChefDeChantier($employe3);
        $manager->persist($chantier2);

        $chantier3 = new Chantier();
        $chantier3->setNom('Extension Maison');
        $chantier3->setNumeroDeLaVoie('789');
        $chantier3->setTypeDeVoie('Boulevard');
        $chantier3->setLibelleDeLaVoie('des Champs-Élysées');
        $chantier3->setCodePostal('75008');
        $chantier3->setVille('Paris');
        $chantier3->setDateDeDebut(new \DateTime('2025-03-11'));
        $chantier3->setDateDeFin(new \DateTime('2025-03-15'));
        $chantier3->setStatut('A venir');
        $chantier3->setDateTacheSuivante($chantier3->getDateDeDebut());
        $chantier3->setClient($client3);
        $chantier3->setChefDeChantier($employe3);
        $manager->persist($chantier3);

        $chantier4 = new Chantier();
        $chantier4->setNom('Rénovation Appartement');
        $chantier4->setNumeroDeLaVoie('321');
        $chantier4->setTypeDeVoie('Rue');
        $chantier4->setLibelleDeLaVoie('Saint-Honoré');
        $chantier4->setCodePostal('75001');
        $chantier4->setVille('Paris');
        $chantier4->setDateDeDebut(new \DateTime('2025-03-16'));
        $chantier4->setDateDeFin(new \DateTime('2025-03-20'));
        $chantier4->setStatut('A venir');
        $chantier4->setDateTacheSuivante($chantier4->getDateDeDebut());
        $chantier4->setClient($client4);
        $chantier4->setChefDeChantier($employe4);
        $manager->persist($chantier4);

        $chantier5 = new Chantier();
        $chantier5->setNom('Construction Villa');
        $chantier5->setNumeroDeLaVoie('654');
        $chantier5->setTypeDeVoie('Allée');
        $chantier5->setLibelleDeLaVoie('des Roses');
        $chantier5->setCodePostal('13000');
        $chantier5->setVille('Marseille');
        $chantier5->setDateDeDebut(new \DateTime('2025-03-21'));
        $chantier5->setDateDeFin(new \DateTime('2025-03-25'));
        $chantier5->setStatut('A venir');
        $chantier5->setDateTacheSuivante($chantier5->getDateDeDebut());
        $chantier5->setClient($client5);
        $chantier5->setChefDeChantier($employe1);
        $manager->persist($chantier5);

        $chantier6 = new Chantier();
        $chantier6->setNom('Rénovation Bureau');
        $chantier6->setNumeroDeLaVoie('987');
        $chantier6->setTypeDeVoie('Avenue');
        $chantier6->setLibelleDeLaVoie('des Fleurs');
        $chantier6->setCodePostal('75009');
        $chantier6->setVille('Paris');
        $chantier6->setDateDeDebut(new \DateTime('2025-03-26'));
        $chantier6->setDateDeFin(new \DateTime('2025-03-30'));
        $chantier6->setStatut('A venir');
        $chantier6->setDateTacheSuivante($chantier6->getDateDeDebut());
        $chantier6->setClient($client6);
        $chantier6->setChefDeChantier($employe2);
        $manager->persist($chantier6);

        $manager->flush();
    }
}

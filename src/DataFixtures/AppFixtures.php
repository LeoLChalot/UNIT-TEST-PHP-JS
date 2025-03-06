<?php

namespace App\DataFixtures;

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
        $employe2->setEstChefDeChantier(false);
        $employe2->setDisponible(true);
        $manager->persist($employe2);

        $employe3 = new Employe();
        $employe3->setNom('Durand');
        $employe3->setPrenom('Paul');
        $employe3->setTelephone('061234567894');
        $employe3->setMetier($metier3);
        $employe3->setEstChefDeChantier(false);
        $employe3->setDisponible(true);
        $manager->persist($employe3);

        $employe4 = new Employe();
        $employe4->setNom('Lefevre');
        $employe4->setPrenom('Julie');
        $employe4->setTelephone('061234567895');
        $employe4->setMetier($metier4);
        $employe4->setEstChefDeChantier(false);
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

        $manager->flush();

        

    }
}

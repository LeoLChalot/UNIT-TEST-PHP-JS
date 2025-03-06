<?php

namespace App\DataFixtures;

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
        $user2->setNom('classique');
        $user2->setPrenom('admin');
        $user2->setTelephone('061234567891');
        $user2->setEmail('admin@admin.fr');
        $hashedPassword = $this->passwordHasher->hashPassword($user2, 'admin');
        $user2->setPassword($hashedPassword);
        $user2->setRoles(['ROLE_ADMIN']);
        $manager->persist($user2);

        $manager->flush();
    }
}

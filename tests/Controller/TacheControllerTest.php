<?php

namespace App\Tests\Controller;

use App\Entity\Chantier;
use App\Entity\Employe;
use App\Entity\Tache;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ObjectRepository;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class TacheControllerTest extends WebTestCase
{
    private KernelBrowser $client;
    private EntityManagerInterface $manager;
    private ObjectRepository $tacheRepository;

    protected function setUp(): void
    {
        $this->client = static::createClient();
        $this->client->setServerParameters([
            'HTTP_HOST' => '127.0.0.1:8000',
        ]);
        $container = static::getContainer();
        $this->manager = $container->get('doctrine.orm.entity_manager');
        $userRepository = $this->manager->getRepository(User::class);
        foreach ($userRepository->findAll() as $user) {
            $this->manager->remove($user);
        }
        $this->manager->flush();
        /** @var UserPasswordHasherInterface $passwordHasher */
        $passwordHasher = $container->get('security.user_password_hasher');
        $user = (new User())->setEmail('admin@admin.fr');
        $user->setNom('admin');
        $user->setPrenom('admin');
        $user->setTelephone('061234567891');
        $user->setRoles(['ROLE_ADMIN']);
        $user->setPassword($passwordHasher->hashPassword($user, 'admin'));
        $this->manager->persist($user);
        $this->manager->flush();
    }

    protected function tearDown(): void
    {
        parent::tearDown();
        $this->manager->close();
    }

    public function testLoginValidCredentials(): void
    {
        $this->client->request('GET', '/');
        self::assertResponseIsSuccessful();
        $this->client->submitForm('Se connecter', [
            '_username' => 'admin@admin.fr',
            '_password' => 'admin',
        ]);
        self::assertResponseRedirects('/admin/dashboard'); // Vérifie la redirection vers le tableau de bord
        $this->client->followRedirect();
        self::assertResponseIsSuccessful();
    }

    public function testIndex(): void
    {
        $this->client->request('GET', '/admin/tache');
        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('h1', 'Liste des tâches');
    }

    public function testNew(): void
    {
        // Créez un chantier et un employé pour les tests
        $chantier = new Chantier();
        $chantier->setNom('Test Chantier');
        $this->manager->persist($chantier);

        $employe = new Employe();
        $employe->setNom('Test');
        $employe->setPrenom('Employe');
        $this->manager->persist($employe);

        $this->manager->flush();

        $crawler = $this->client->request('GET', '/admin/tache/new');
        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('h1', 'Ajouter une tâche');

        // Soumet le formulaire avec des données valides
        $form = $crawler->selectButton('Save')->form([
            'tache[description]' => 'Test Tâche',
            'tache[statut]' => 'En cours',
            'tache[duree]' => 2.5,
            'tache[chantier]' => $chantier->getId(),
            'tache[employe]' => $employe->getId(),
        ]);

        $this->client->submit($form);
        $this->assertResponseRedirects('/admin/tache'); // Vérifie la redirection
        $this->client->followRedirect(); // Suit la redirection
        $this->assertResponseIsSuccessful(); // Vérifie que la page suivante s'affiche
        $this->assertSelectorTextContains('.alert-success', 'Tâche créée avec succès'); // Vérifie le message flash
    }

    public function testShow(): void
    {
        $tache = new Tache();
        $tache->setDescription('Test Tâche');
        $tache->setDuree(2.5);

        $this->manager->persist($tache);
        $this->manager->flush();

        $this->client->request('GET', '/admin/tache/' . $tache->getId());
        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('h1', 'Détails de la tâche');
    }

    public function testEdit(): void
    {
        // Créez un chantier et un employé pour les tests
        $chantier = new Chantier();
        $chantier->setNom('Test Chantier');
        $this->manager->persist($chantier);

        $employe = new Employe();
        $employe->setNom('Test');
        $employe->setPrenom('Employe');
        $this->manager->persist($employe);

        $tache = new Tache();
        $tache->setDescription('Test Tâche');
        $tache->setDuree(2.5);
        $tache->setChantier($chantier);
        $tache->addEmploye($employe);

        $this->manager->persist($tache);
        $this->manager->flush();

        $crawler = $this->client->request('GET', '/admin/tache/' . $tache->getId() . '/edit');
        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('h1', 'Modifier la tâche');

        $form = $crawler->selectButton('Enregistrer')->form([
            'tache[description]' => 'Tâche Modifiée',
            'tache[statut]' => 'En cours',
            'tache[duree]' => 3.0,
            'tache[chantier]' => $chantier->getId(),
            'tache[employe]' => $employe->getId(),
        ]);

        $this->client->submit($form);
        $this->assertResponseRedirects('/admin/tache');
        $this->client->followRedirect();
        $this->assertSelectorTextContains('.alert-success', 'Tâche modifiée avec succès');
    }

    public function testDelete(): void
    {
        // Créez un chantier et un employé pour les tests
        $chantier = new Chantier();
        $chantier->setNom('Test Chantier');
        $this->manager->persist($chantier);

        $employe = new Employe();
        $employe->setNom('Test');
        $employe->setPrenom('Employe');
        $this->manager->persist($employe);

        $tache = new Tache();
        $tache->setDescription('Test Tâche');
        $tache->setDuree(2.5);
        $tache->setStatut('En cours');
        $tache->setChantier($chantier);
        $tache->addEmploye($employe);

        $this->manager->persist($tache);
        $this->manager->flush();

        $crawler = $this->client->request('GET', '/admin/tache/' . $tache->getId());
        $this->assertResponseIsSuccessful();

        $form = $crawler->selectButton('Supprimer')->form();
        $this->client->submit($form);
        $this->assertResponseRedirects('/admin/tache');
        $this->client->followRedirect();
        $this->assertSelectorTextContains('.alert .alert-success', 'Tâche supprimée avec succès');
    }
}

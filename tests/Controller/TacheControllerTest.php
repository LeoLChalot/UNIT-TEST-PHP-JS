<?php

namespace App\Tests\Controller;

use App\Entity\Tache;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ObjectRepository;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class TacheControllerTest extends WebTestCase
{
    private KernelBrowser $client;
    private EntityManagerInterface $manager;
    private ObjectRepository $tacheRepository;

    protected function setUp(): void
    {
        $this->client = static::createClient();
        $this->manager = static::getContainer()->get('doctrine')->getManager();
        $this->tacheRepository = $this->manager->getRepository(Tache::class);

        // Démarre une transaction
        $this->manager->beginTransaction();
    }

    protected function tearDown(): void
    {
        // Annule la transaction pour nettoyer la base de données
        $this->manager->rollback();
        parent::tearDown();
    }

    public function testIndex(): void
    {
        $this->client->request('GET', '/admin/tache');
        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('h1', 'Liste des tâches');
    }

    public function testNew(): void
    {
        $crawler = $this->client->request('GET', '/admin/tache/new');
        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('h1', 'Ajouter une tâche');

        $form = $crawler->selectButton('Enregistrer')->form([
            'tache[description]' => 'Test Tâche',
            'tache[statut]' => 'En cours',
            'tache[duree]' => 2.5,
            'tache[chantier]' => 1,
            'tache[employe]' => 1,
        ]);

        $this->client->submit($form);
        $this->assertResponseRedirects('/admin/tache');
        $this->client->followRedirect();
        $this->assertSelectorTextContains('.alert-success', 'Tâche créée avec succès');
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
        $tache = new Tache();
        $tache->setDescription('Test Tâche');
        $tache->setDuree(2.5);

        $this->manager->persist($tache);
        $this->manager->flush();

        $crawler = $this->client->request('GET', '/admin/tache/' . $tache->getId() . '/edit');
        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('h1', 'Modifier la tâche');

        $form = $crawler->selectButton('Enregistrer')->form([
            'tache[description]' => 'Test Tâche',
            'tache[statut]' => 'En cours',
            'tache[duree]' => 2.5,
            'tache[chantier]' => 1,
            'tache[employe]' => 1,
        ]);

        $this->client->submit($form);
        $this->assertResponseRedirects('/admin/tache');
        $this->client->followRedirect();
        $this->assertSelectorTextContains('.alert-success', 'Tâche modifiée avec succès');
    }

    public function testDelete(): void
    {
        $tache = new Tache();
        $tache->setDescription('Test Tâche');
        $tache->setDuree(2.5);

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
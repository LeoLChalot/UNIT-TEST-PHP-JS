<?php

namespace App\Tests\Controller;

use App\Entity\Tache;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

final class TacheControllerTest extends WebTestCase
{
    private KernelBrowser $client;
    private EntityManagerInterface $manager;
    private EntityRepository $tacheRepository;
    private string $path = '/tache/';

    protected function setUp(): void
    {
        $this->client = static::createClient();
        $this->manager = static::getContainer()->get('doctrine')->getManager();
        $this->tacheRepository = $this->manager->getRepository(Tache::class);

        foreach ($this->tacheRepository->findAll() as $object) {
            $this->manager->remove($object);
        }

        $this->manager->flush();
    }

    public function testIndex(): void
    {
        $this->client->followRedirects();
        $crawler = $this->client->request('GET', $this->path);

        self::assertResponseStatusCodeSame(200);
        self::assertPageTitleContains('Tache index');

        // Use the $crawler to perform additional assertions e.g.
        // self::assertSame('Some text on the page', $crawler->filter('.p')->first());
    }

    public function testNew(): void
    {
        $this->markTestIncomplete();
        $this->client->request('GET', sprintf('%snew', $this->path));

        self::assertResponseStatusCodeSame(200);

        $this->client->submitForm('Save', [
            'tache[description]' => 'Testing',
            'tache[statut]' => 'Testing',
            'tache[date_de_debut]' => 'Testing',
            'tache[date_de_fin]' => 'Testing',
            'tache[chantier]' => 'Testing',
            'tache[employes]' => 'Testing',
        ]);

        self::assertResponseRedirects($this->path);

        self::assertSame(1, $this->tacheRepository->count([]));
    }

    public function testShow(): void
    {
        $this->markTestIncomplete();
        $fixture = new Tache();
        $fixture->setDescription('My Title');
        $fixture->setStatut('My Title');
        $fixture->setDate_de_debut('My Title');
        $fixture->setDate_de_fin('My Title');
        $fixture->setChantier('My Title');
        $fixture->setEmployes('My Title');

        $this->manager->persist($fixture);
        $this->manager->flush();

        $this->client->request('GET', sprintf('%s%s', $this->path, $fixture->getId()));

        self::assertResponseStatusCodeSame(200);
        self::assertPageTitleContains('Tache');

        // Use assertions to check that the properties are properly displayed.
    }

    public function testEdit(): void
    {
        $this->markTestIncomplete();
        $fixture = new Tache();
        $fixture->setDescription('Value');
        $fixture->setStatut('Value');
        $fixture->setDate_de_debut('Value');
        $fixture->setDate_de_fin('Value');
        $fixture->setChantier('Value');
        $fixture->setEmployes('Value');

        $this->manager->persist($fixture);
        $this->manager->flush();

        $this->client->request('GET', sprintf('%s%s/edit', $this->path, $fixture->getId()));

        $this->client->submitForm('Update', [
            'tache[description]' => 'Something New',
            'tache[statut]' => 'Something New',
            'tache[date_de_debut]' => 'Something New',
            'tache[date_de_fin]' => 'Something New',
            'tache[chantier]' => 'Something New',
            'tache[employes]' => 'Something New',
        ]);

        self::assertResponseRedirects('/tache/');

        $fixture = $this->tacheRepository->findAll();

        self::assertSame('Something New', $fixture[0]->getDescription());
        self::assertSame('Something New', $fixture[0]->getStatut());
        self::assertSame('Something New', $fixture[0]->getDate_de_debut());
        self::assertSame('Something New', $fixture[0]->getDate_de_fin());
        self::assertSame('Something New', $fixture[0]->getChantier());
        self::assertSame('Something New', $fixture[0]->getEmployes());
    }

    public function testRemove(): void
    {
        $this->markTestIncomplete();
        $fixture = new Tache();
        $fixture->setDescription('Value');
        $fixture->setStatut('Value');
        $fixture->setDate_de_debut('Value');
        $fixture->setDate_de_fin('Value');
        $fixture->setChantier('Value');
        $fixture->setEmployes('Value');

        $this->manager->persist($fixture);
        $this->manager->flush();

        $this->client->request('GET', sprintf('%s%s', $this->path, $fixture->getId()));
        $this->client->submitForm('Delete');

        self::assertResponseRedirects('/tache/');
        self::assertSame(0, $this->tacheRepository->count([]));
    }
}

<?php

namespace App\Tests\Controller;

use App\Entity\Chantier;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

final class ChantierControllerTest extends WebTestCase
{
    private KernelBrowser $client;
    private EntityManagerInterface $manager;
    private EntityRepository $chantierRepository;
    private string $path = '/chantier/';

    protected function setUp(): void
    {
        $this->client = static::createClient();
        $this->manager = static::getContainer()->get('doctrine')->getManager();
        $this->chantierRepository = $this->manager->getRepository(Chantier::class);

        foreach ($this->chantierRepository->findAll() as $object) {
            $this->manager->remove($object);
        }

        $this->manager->flush();
    }

    public function testIndex(): void
    {
        $this->client->followRedirects();
        $crawler = $this->client->request('GET', $this->path);

        self::assertResponseStatusCodeSame(200);
        self::assertPageTitleContains('Chantier index');

        // Use the $crawler to perform additional assertions e.g.
        // self::assertSame('Some text on the page', $crawler->filter('.p')->first());
    }

    public function testNew(): void
    {
        $this->markTestIncomplete();
        $this->client->request('GET', sprintf('%snew', $this->path));

        self::assertResponseStatusCodeSame(200);

        $this->client->submitForm('Save', [
            'chantier[date_de_debut]' => 'Testing',
            'chantier[date_de_fin]' => 'Testing',
            'chantier[chef_de_chantier]' => 'Testing',
            'chantier[client]' => 'Testing',
        ]);

        self::assertResponseRedirects($this->path);

        self::assertSame(1, $this->chantierRepository->count([]));
    }

    public function testShow(): void
    {
        $this->markTestIncomplete();
        $fixture = new Chantier();
        $fixture->setDate_de_debut('My Title');
        $fixture->setDate_de_fin('My Title');
        $fixture->setChef_de_chantier('My Title');
        $fixture->setClient('My Title');

        $this->manager->persist($fixture);
        $this->manager->flush();

        $this->client->request('GET', sprintf('%s%s', $this->path, $fixture->getId()));

        self::assertResponseStatusCodeSame(200);
        self::assertPageTitleContains('Chantier');

        // Use assertions to check that the properties are properly displayed.
    }

    public function testEdit(): void
    {
        $this->markTestIncomplete();
        $fixture = new Chantier();
        $fixture->setDate_de_debut('Value');
        $fixture->setDate_de_fin('Value');
        $fixture->setChef_de_chantier('Value');
        $fixture->setClient('Value');

        $this->manager->persist($fixture);
        $this->manager->flush();

        $this->client->request('GET', sprintf('%s%s/edit', $this->path, $fixture->getId()));

        $this->client->submitForm('Update', [
            'chantier[date_de_debut]' => 'Something New',
            'chantier[date_de_fin]' => 'Something New',
            'chantier[chef_de_chantier]' => 'Something New',
            'chantier[client]' => 'Something New',
        ]);

        self::assertResponseRedirects('/chantier/');

        $fixture = $this->chantierRepository->findAll();

        self::assertSame('Something New', $fixture[0]->getDate_de_debut());
        self::assertSame('Something New', $fixture[0]->getDate_de_fin());
        self::assertSame('Something New', $fixture[0]->getChef_de_chantier());
        self::assertSame('Something New', $fixture[0]->getClient());
    }

    public function testRemove(): void
    {
        $this->markTestIncomplete();
        $fixture = new Chantier();
        $fixture->setDate_de_debut('Value');
        $fixture->setDate_de_fin('Value');
        $fixture->setChef_de_chantier('Value');
        $fixture->setClient('Value');

        $this->manager->persist($fixture);
        $this->manager->flush();

        $this->client->request('GET', sprintf('%s%s', $this->path, $fixture->getId()));
        $this->client->submitForm('Delete');

        self::assertResponseRedirects('/chantier/');
        self::assertSame(0, $this->chantierRepository->count([]));
    }
}

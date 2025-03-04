<?php

namespace App\Tests\Controller;

use App\Entity\Employe;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

final class EmployeControllerTest extends WebTestCase
{
    private KernelBrowser $client;
    private EntityManagerInterface $manager;
    private EntityRepository $employeRepository;
    private string $path = '/employe/';

    protected function setUp(): void
    {
        $this->client = static::createClient();
        $this->manager = static::getContainer()->get('doctrine')->getManager();
        $this->employeRepository = $this->manager->getRepository(Employe::class);

        foreach ($this->employeRepository->findAll() as $object) {
            $this->manager->remove($object);
        }

        $this->manager->flush();
    }

    public function testIndex(): void
    {
        $this->client->followRedirects();
        $crawler = $this->client->request('GET', $this->path);

        self::assertResponseStatusCodeSame(200);
        self::assertPageTitleContains('Employe index');

        // Use the $crawler to perform additional assertions e.g.
        // self::assertSame('Some text on the page', $crawler->filter('.p')->first());
    }

    public function testNew(): void
    {
        $this->markTestIncomplete();
        $this->client->request('GET', sprintf('%snew', $this->path));

        self::assertResponseStatusCodeSame(200);

        $this->client->submitForm('Save', [
            'employe[nom]' => 'Testing',
            'employe[prenom]' => 'Testing',
            'employe[telephone]' => 'Testing',
            'employe[est_chef_de_chantier]' => 'Testing',
            'employe[metier]' => 'Testing',
            'employe[taches]' => 'Testing',
        ]);

        self::assertResponseRedirects($this->path);

        self::assertSame(1, $this->employeRepository->count([]));
    }

    public function testShow(): void
    {
        $this->markTestIncomplete();
        $fixture = new Employe();
        $fixture->setNom('My Title');
        $fixture->setPrenom('My Title');
        $fixture->setTelephone('My Title');
        $fixture->setEst_chef_de_chantier('My Title');
        $fixture->setMetier('My Title');
        $fixture->setTaches('My Title');

        $this->manager->persist($fixture);
        $this->manager->flush();

        $this->client->request('GET', sprintf('%s%s', $this->path, $fixture->getId()));

        self::assertResponseStatusCodeSame(200);
        self::assertPageTitleContains('Employe');

        // Use assertions to check that the properties are properly displayed.
    }

    public function testEdit(): void
    {
        $this->markTestIncomplete();
        $fixture = new Employe();
        $fixture->setNom('Value');
        $fixture->setPrenom('Value');
        $fixture->setTelephone('Value');
        $fixture->setEst_chef_de_chantier('Value');
        $fixture->setMetier('Value');
        $fixture->setTaches('Value');

        $this->manager->persist($fixture);
        $this->manager->flush();

        $this->client->request('GET', sprintf('%s%s/edit', $this->path, $fixture->getId()));

        $this->client->submitForm('Update', [
            'employe[nom]' => 'Something New',
            'employe[prenom]' => 'Something New',
            'employe[telephone]' => 'Something New',
            'employe[est_chef_de_chantier]' => 'Something New',
            'employe[metier]' => 'Something New',
            'employe[taches]' => 'Something New',
        ]);

        self::assertResponseRedirects('/employe/');

        $fixture = $this->employeRepository->findAll();

        self::assertSame('Something New', $fixture[0]->getNom());
        self::assertSame('Something New', $fixture[0]->getPrenom());
        self::assertSame('Something New', $fixture[0]->getTelephone());
        self::assertSame('Something New', $fixture[0]->getEst_chef_de_chantier());
        self::assertSame('Something New', $fixture[0]->getMetier());
        self::assertSame('Something New', $fixture[0]->getTaches());
    }

    public function testRemove(): void
    {
        $this->markTestIncomplete();
        $fixture = new Employe();
        $fixture->setNom('Value');
        $fixture->setPrenom('Value');
        $fixture->setTelephone('Value');
        $fixture->setEst_chef_de_chantier('Value');
        $fixture->setMetier('Value');
        $fixture->setTaches('Value');

        $this->manager->persist($fixture);
        $this->manager->flush();

        $this->client->request('GET', sprintf('%s%s', $this->path, $fixture->getId()));
        $this->client->submitForm('Delete');

        self::assertResponseRedirects('/employe/');
        self::assertSame(0, $this->employeRepository->count([]));
    }
}

<?php

namespace App\Tests;

use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class LoginControllerTest extends WebTestCase
{
    private KernelBrowser $client;

    protected function setUp(): void
    {
        // Create a new client to browse the application

        $this->client = static::createClient();
        $container = static::getContainer();
        $em = $container->get('doctrine.orm.entity_manager');
        $userRepository = $em->getRepository(User::class);

        // Remove any existing users from the test database
        foreach ($userRepository->findAll() as $user) {
            $em->remove($user);
        }
        $em->flush();
        // Create a User fixture
        /** @var UserPasswordHasherInterface $passwordHasher */
        $passwordHasher = $container->get('security.user_password_hasher');

        $user = (new User())->setEmail('l.larouchalot@gmail.com');
        $user->setPassword($passwordHasher->hashPassword($user, '@3A42f6be3e6'));

        $em->persist($user);
        $em->flush();
    }

    public function testLoginInvalidMail(): void
    {
        $this->client->setServerParameters([
            'HTTP_HOST' => '127.0.0.1:8000',
        ]);

        $this->client->request('GET', '/');
        self::assertResponseIsSuccessful();

        $this->client->submitForm('Sign in', [
            '_username' => 'doesNotExist@example.com',
            '_password' => '@3A42f6be3e6',
        ]);

        self::assertResponseRedirects('/');
        $this->client->followRedirect();

        // Ensure we do not reveal if the user exists or not.
        self::assertSelectorTextContains('.alert-danger', 'Invalid credentials.');
    }

    public function testLoginInvalidPasword(): void
    {
        $this->client->setServerParameters([
            'HTTP_HOST' => '127.0.0.1:8000',
        ]);

        $this->client->request('GET', '/');
        self::assertResponseIsSuccessful();

        $this->client->submitForm('Sign in', [
            '_username' => 'l.larouchalot@gmail.com',
            '_password' => 'bad-password',
        ]);

        self::assertResponseRedirects('/');
        $this->client->followRedirect();

        // Ensure we do not reveal the user exists but the password is wrong.
        self::assertSelectorTextContains('.alert-danger', 'Invalid credentials.');
    }

    public function testLoginValidCredentials(): void
    {
        $this->client->setServerParameters([
            'HTTP_HOST' => '127.0.0.1:8000',
        ]);

        $this->client->request('GET', '/');
        self::assertResponseIsSuccessful();

        // Success - Login with valid credentials is allowed.
        $this->client->submitForm('Sign in', [
            '_username' => 'l.larouchalot@gmail.com',
            '_password' => '@3A42f6be3e6',
        ]);

        self::assertResponseRedirects('/chantier');
        $this->client->followRedirect();

        self::assertSelectorNotExists('.alert-danger');
        self::assertResponseIsSuccessful();
    }
}


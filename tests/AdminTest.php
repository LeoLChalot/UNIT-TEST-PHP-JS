<?php

namespace App\Tests;

use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AdminTest extends WebTestCase
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

        $user = (new User())->setEmail('admin@admin.fr');
        $user->setRoles(['ROLE_SUPER_ADMIN']);
        $user->setPassword($passwordHasher->hashPassword($user, 'admin'));

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
            '_password' => 'admin',
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
            '_username' => 'admin@admin.fr',
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
            '_username' => 'admin@admin.fr',
            '_password' => 'admin',
        ]);

        self::assertResponseRedirects('/admin/dashboard');
        $this->client->followRedirect();

        self::assertSelectorNotExists('.alert-danger');
        self::assertResponseIsSuccessful();

        // Store the session for reuse in other tests
        $session = $this->client->getRequest()->getSession();
        $this->client->getCookieJar()->set(new \Symfony\Component\BrowserKit\Cookie(
            $session->getName(),
            $session->getId()
        ));
    }

    public function testAdminDashboardAccess(): void
    {
        $this->client->setServerParameters([
            'HTTP_HOST' => '127.0.0.1:8000',
        ]);
    
        // Request the admin dashboard
        $this->client->request('GET', '/admin/dashboard');
    
        // Check if the response is a redirect
        self::assertResponseRedirects();
    
        // Follow the redirect
        $this->client->followRedirect();
    
        // Check if the redirected page is successful
        self::assertResponseIsSuccessful();
    }

    public function testAdminRegisterNoAccess(): void
    {
        $this->client->setServerParameters([
            'HTTP_HOST' => '127.0.0.1:8000',
        ]);
    
        // Request the admin dashboard
        $this->client->request('GET', '/superadmin/register');
        
        // Check if the response is a redirect to the login page
        self::assertResponseRedirects('/');

        // Follow the redirect
        $this->client->followRedirect();

        // Check if the redirected page is successful
        self::assertResponseIsSuccessful();
    }

    public function testMetierAccess(): void
    {
        $this->client->setServerParameters([
            'HTTP_HOST' => '127.0.0.1:8000',
        ]);
    
        // Request the admin dashboard
        $this->client->request('GET', '/admin/chantier');
    
        // Check if the response is a redirect
        self::assertResponseRedirects();
    
        // Follow the redirect
        $this->client->followRedirect();
    
        // Check if the redirected page is successful
        self::assertResponseIsSuccessful();
    }

    public function testClientAccess(): void
    {
        $this->client->setServerParameters([
            'HTTP_HOST' => '127.0.0.1:8000',
        ]);
    
        // Request the admin dashboard
        $this->client->request('GET', '/admin/client');
    
        // Check if the response is a redirect
        self::assertResponseRedirects();
    
        // Follow the redirect
        $this->client->followRedirect();
    
        // Check if the redirected page is successful
        self::assertResponseIsSuccessful();
    }

    public function testEmployeAccess(): void
    {
        $this->client->setServerParameters([
            'HTTP_HOST' => '127.0.0.1:8000',
        ]);
    
        // Request the admin dashboard
        $this->client->request('GET', '/admin/employe');
    
        // Check if the response is a redirect
        self::assertResponseRedirects();
    
        // Follow the redirect
        $this->client->followRedirect();
    
        // Check if the redirected page is successful
        self::assertResponseIsSuccessful();
    }

    public function testTacheAccess(): void
    {
        $this->client->setServerParameters([
            'HTTP_HOST' => '127.0.0.1:8000',
        ]);
    
        // Request the admin dashboard
        $this->client->request('GET', '/admin/tache');
    
        // Check if the response is a redirect
        self::assertResponseRedirects();
    
        // Follow the redirect
        $this->client->followRedirect();
    
        // Check if the redirected page is successful
        self::assertResponseIsSuccessful();
    }
}

<?php

namespace App\Tests;

use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
class SuperAdminTest extends WebTestCase
{
    private KernelBrowser $client;

    /**
     * ## Configuration initiale pour les tests d'administration.
     *
     * Cette méthode est exécutée avant chaque test pour configurer l'environnement de test.
     * Elle effectue les actions suivantes :
     * - Crée un client HTTP pour simuler les requêtes.
     * - Définit les paramètres du serveur pour le client HTTP.
     * - Récupère le conteneur de services et le gestionnaire d'entités Doctrine.
     * - Supprime tous les utilisateurs existants de la base de données de test.
     * - Crée un utilisateur avec le rôle "ROLE_SUPER_ADMIN" et un mot de passe haché.
     * - Persiste le nouvel utilisateur dans la base de données.
     *
     * @return void
     */
    protected function setUp(): void
    {
        $this->client = static::createClient();
        $this->client->setServerParameters([
            'HTTP_HOST' => '127.0.0.1:8000',
        ]);
        $container = static::getContainer();
        $em = $container->get('doctrine.orm.entity_manager');
        $userRepository = $em->getRepository(User::class);
        foreach ($userRepository->findAll() as $user) {
            $em->remove($user);
        }
        $em->flush();
        /** @var UserPasswordHasherInterface $passwordHasher */
        $passwordHasher = $container->get('security.user_password_hasher');
        $user = (new User())->setEmail('superadmin@admin.fr');
        $user->setNom('super');
        $user->setPrenom('admin');
        $user->setTelephone('061234567890');
        $user->setRoles(['ROLE_SUPER_ADMIN']);
        $user->setPassword($passwordHasher->hashPassword($user, 'admin'));
        $em->persist($user);
        $em->flush();
    }

    /**
     * ## Teste le scénario où un utilisateur tente de se connecter avec un email invalide.
     *
     * Étapes du test :
     * 1. Envoie une requête GET à la page d'accueil.
     * 2. Vérifie que la réponse est réussie.
     * 3. Soumet le formulaire de connexion avec des identifiants invalides.
     * 4. Vérifie que la réponse redirige vers la page d'accueil.
     * 5. Suit la redirection.
     * 6. Vérifie que le message d'erreur "Invalid credentials." est affiché.
     */
    public function testLoginInvalidMail(): void
    {
        $this->client->request('GET', '/');
        self::assertResponseIsSuccessful();
        $this->client->submitForm('Se connecter', [
            '_username' => 'doesNotExist@example.com',
            '_password' => 'admin',
        ]);
        self::assertResponseRedirects('/');
        $this->client->followRedirect();
        self::assertSelectorTextContains('.alert-danger', 'Invalid credentials.');
    }

    /**
     * ## Teste le scénario où un utilisateur tente de se connecter avec un mot de passe invalide.
     *
     * Étapes du test :
     * 1. Envoie une requête GET à la page d'accueil.
     * 2. Vérifie que la réponse est réussie.
     * 3. Soumet le formulaire de connexion avec un nom d'utilisateur valide et un mot de passe incorrect.
     * 4. Vérifie que la réponse redirige vers la page d'accueil.
     * 5. Suit la redirection.
     * 6. Vérifie que le message d'erreur "Invalid credentials." est affiché, indiquant que les informations d'identification sont incorrectes.
     */
    public function testLoginInvalidPasword(): void
    {
        $this->client->request('GET', '/');
        self::assertResponseIsSuccessful();
        $this->client->submitForm('Se connecter', [
            '_username' => 'admin@admin.fr',
            '_password' => 'bad-password',
        ]);
        self::assertResponseRedirects('/');
        $this->client->followRedirect();
        self::assertSelectorTextContains('.alert-danger', 'Invalid credentials.');
    }

    /**
     * ## Teste la connexion avec des identifiants valides.
     *
     * Étapes du test :
     * 1. Envoie une requête GET à la racine du site.
     * 2. Vérifie que la réponse est réussie.
     * 3. Soumet le formulaire de connexion avec des identifiants valides.
     * 4. Vérifie que la réponse redirige vers le tableau de bord admin.
     * 5. Suit la redirection.
     * 6. Vérifie qu'il n'y a pas d'alerte de danger.
     * 7. Vérifie que la réponse est réussie.
     * 8. Stocke la session pour la réutiliser dans d'autres tests.
     *
     * @return void
     */
    public function testLoginValidCredentials(): void
    {
        $this->client->request('GET', '/');
        self::assertResponseIsSuccessful();
        $this->client->submitForm('Se connecter', [
            '_username' => 'superadmin@admin.fr',
            '_password' => 'superadmin',
        ]);
        self::assertResponseRedirects('/admin/dashboard');
        $this->client->followRedirect();
        self::assertSelectorNotExists('.alert-danger');
        self::assertResponseIsSuccessful();
        $session = $this->client->getRequest()->getSession();
        $this->client->getCookieJar()->set(new \Symfony\Component\BrowserKit\Cookie(
            $session->getName(),
            $session->getId()
        ));
    }

    /**
     * ## Teste que l'accès à la page de registre de superadmin est redirigé pour les utilisateurs non autorisés.
     *
     * Cette méthode effectue les actions suivantes :
     * 1. Envoie une requête GET à l'URL '/superadmin/register'.
     * 2. Vérifie que la réponse redirige vers la page d'accueil ('/').
     * 3. Suit la redirection.
     * 4. Vérifie que la réponse finale est réussie (statut HTTP 200).
     *
     * @return void
     */
    public function testAdminRegisterNoAccess(): void
    {
        $this->client->request('GET', '/superadmin/register');
        self::assertResponseRedirects('/');
        $this->client->followRedirect();
        self::assertResponseIsSuccessful();
    }
}
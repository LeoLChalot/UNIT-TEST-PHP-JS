<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

final class DashboardControllerTest extends WebTestCase
{
    public function AdminDashboardAccessTest(): void
    {
        $client = static::createClient();
        $client->setServerParameters([
            'HTTP_HOST' => '127.0.0.1:8000',
        ]);
        $client->request('GET', '/admin/dashboard');

        self::assertResponseIsSuccessful();
    }

}

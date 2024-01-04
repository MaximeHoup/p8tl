<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class DefaultControllerTest extends WebTestCase
{
    private $client;

    public function setUp(): void
    {
        $this->client = static::createClient();
    }

    public function testAccessIndexPage()
    {
        $this->client->request('GET', '/');
        $this->assertResponseIsSuccessful();
        $this->assertAnySelectorTextContains('h1', 'Bienvenue');
    }
}

<?php

namespace App\Tests\Controller;

use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class LoginControllerTest extends WebTestCase
{
    private $client;

    public function setUp(): void
    {
        $this->client = static::createClient();
    }

    public function loginUser($mail): void
    {
        $userRepository = static::getContainer()->get(UserRepository::class);
        $testUser = $userRepository->findOneByEmail(['email' => $mail]);
        $this->client->loginUser($testUser);
    }

    public function testLoginPage()
    {
        $crawler = $this->client->request('GET', '/login');
        $this->assertResponseIsSuccessful();
        $this->assertAnySelectorTextContains('label', 'Email');
        $this->assertAnySelectorTextContains('label', 'Mot de passe');
        $this->assertAnySelectorTextContains('button', 'Connexion');
    }
}

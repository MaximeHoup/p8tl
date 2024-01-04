<?php

namespace App\Tests\Controller;

use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

class UserControllerTest extends WebTestCase
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

    public function testAccessUsersList()
    {
        $this->loginUser('admintest@mail.fr');
        $this->client->request('GET', '/users');
        $this->assertResponseIsSuccessful();
        $this->assertAnySelectorTextContains('h1', 'Liste des utilisateurs');
    }

    public function testAccessDeniedUsersList()
    {
        $this->loginUser('usertest@mail.fr');
        $this->client->request('GET', '/users');
        $this->assertResponseStatusCodeSame(Response::HTTP_FORBIDDEN);
    }
}

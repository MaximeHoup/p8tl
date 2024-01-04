<?php

namespace App\Tests\Controller;

use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class RegistrationControllerTest extends WebTestCase
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

    public function testRegistrationPageAccessNotAdmin()
    {
        $this->loginUser('usertest@mail.fr');
        $crawler = $this->client->request('GET', '/register');
        $this->assertResponseStatusCodeSame(403);
    }

    public function testRegistrationPageAccessAdmin()
    {
        $this->loginUser('admintest@mail.fr');
        $crawler = $this->client->request('GET', '/register');
        $this->assertResponseStatusCodeSame(200);
    }

    public function testRegistrationNewUser()
    {
        $this->loginUser('admintest@mail.fr');
        $crawler = $this->client->request('GET', '/register');
        $form = $crawler->selectButton('S\'inscrire')->form();
        $this->client->submit($form, [
            'registration_form[username]'    => 'New User',
            'registration_form[email]'    => 'new@user.fr',
            'registration_form[password][first]'    => 'newpassword',
            'registration_form[password][second]'    => 'newpassword',
        ]);
        $this->client->followRedirect();
        $this->assertSelectorExists('.alert-success');
    }

    public function testRegistrationNewAdmin()
    {
        $this->loginUser('admintest@mail.fr');
        $crawler = $this->client->request('GET', '/register');
        $form = $crawler->selectButton('S\'inscrire')->form();
        $form['registration_form[username]'] = 'New Admin';
        $form['registration_form[email]'] = 'new@admin.fr';
        $form['registration_form[password][first]'] = 'newpassword';
        $form['registration_form[password][second]'] = 'newpassword';
        $form['registration_form[roles][0]']->tick();
        $this->client->submit($form);
        $this->client->followRedirect();
        $this->assertSelectorExists('.alert-success');
    }

    public function testEditUser()
    {
        $this->loginUser('admintest@mail.fr');
        $userTest = static::getContainer()->get(UserRepository::class)->findOneBy(['username' => 'New User']);
        $userId = $userTest->getId();
        $crawler = $this->client->request('GET', '/users/' . $userId . '/edit');
        $form = $crawler->selectButton('Modifier')->form();
        $form['registration_form[username]'] = 'User Edit';
        $form['registration_form[email]'] = 'edit@user.fr';
        $form['registration_form[password][first]'] = 'editpassword';
        $form['registration_form[password][second]'] = 'editpassword';
        $this->client->submit($form);
        $this->client->followRedirect();
        $this->assertSelectorExists('.alert-success');
    }
}

<?php

namespace App\Tests\Controller;

use App\Repository\TaskRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class TaskControllerTest extends WebTestCase
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

    public function testTaskList()
    {
        $this->client->request('GET', '/tasks');
        $this->assertResponseIsSuccessful();
        $this->assertAnySelectorTextContains('a', 'Créer une tâche');
    }

    public function testNewTask()
    {
        $this->loginUser('usertest@mail.fr');
        $crawler = $this->client->request('GET', '/tasks/create');
        $form = $crawler->selectButton('Ajouter')->form();
        $this->client->submit($form, [
            'task_form[title]'    => 'The title',
            'task_form[content]' => 'The content',
        ]);

        $this->client->followRedirect();
        $this->assertSelectorExists('.alert-success');
    }

    public function testToggleTask()
    {
        $taskTest = static::getContainer()->get(TaskRepository::class)->findOneBy(['title' => 'Task1']);
        $taskId = $taskTest->getId();
        $this->client->request('GET', '/tasks/' . $taskId . '/toggle');
        $this->client->followRedirect();
        $this->assertSelectorExists('.alert-success');
    }

    public function testEditTask()
    {
        $this->loginUser('usertest@mail.fr');
        $taskTest = static::getContainer()->get(TaskRepository::class)->findOneBy(['title' => 'The title']);
        $taskId = $taskTest->getId();
        $crawler = $this->client->request('GET', '/tasks/' . $taskId . '/edit');
        $form = $crawler->selectButton('Modifier')->form();
        $this->client->submit($form, [
            'task_form[title]'    => 'The new title',
            'task_form[content]' => 'The new content',
        ]);

        $this->client->followRedirect();
        $this->assertSelectorExists('.alert-success');
    }

    public function testDeleteTask()
    {
        $this->loginUser('usertest@mail.fr');
        $taskTest = static::getContainer()->get(TaskRepository::class)->findOneBy(['title' => 'The new title']);
        $taskId = $taskTest->getId();
        $crawler = $this->client->request('GET', '/tasks/' . $taskId . '/delete');
        $this->client->followRedirect();
        $this->assertSelectorExists('.alert-success');
    }

    public function testDeleteTaskWrongUser()
    {
        $this->loginUser('usertest@mail.fr');
        $taskTest = static::getContainer()->get(TaskRepository::class)->findOneBy(['title' => 'Task5']);
        $taskId = $taskTest->getId();
        $crawler = $this->client->request('GET', '/tasks/' . $taskId . '/delete');
        $this->assertResponseStatusCodeSame(401);
    }
}

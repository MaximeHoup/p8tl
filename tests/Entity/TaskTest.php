<?php

namespace App\Tests\Entity;

use App\Entity\Task;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class TaskTest extends WebTestCase
{
    private $task;

    public function setUp(): void
    {
        $this->task = new Task();
    }

    public function testCreatedAt()
    {
        $date = new \DateTime();
        $this->task->setCreatedAt($date);
        $this->assertSame($date, $this->task->getCreatedAt());
    }

    public function testIsDone()
    {
        $this->task->setIsDone(true);
        $this->assertSame(true, $this->task->isIsDone());
    }
}

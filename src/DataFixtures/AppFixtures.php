<?php

namespace App\DataFixtures;

use App\Entity\Task;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $listUser = [];
        $user = new User();
        $user->setEmail('usertest@mail.fr');
        $user->setUsername('usertest');
        $user->setPassword('passwordtest');
        $user->setRoles([]);
        $manager->persist($user);
        $listUser[] = $user;

        $admin = new User();
        $admin->setEmail('admintest@mail.fr');
        $admin->setUsername('admintest');
        $admin->setPassword('passwordtest');
        $admin->setRoles(['ROLE_ADMIN']);
        $manager->persist($admin);
        $listUser[] = $admin;

        for ($i = 1; $i <= 4; ++$i) {
            $task = new Task();
            $task->setTitle('Task'.$i);
            $task->setContent('Content of task'.$i);
            $task->setUser($user);
            $manager->persist($task);
        }

        for ($j = 5; $j <= 8; ++$j) {
            $task = new Task();
            $task->setTitle('Task'.$j);
            $task->setContent('Content of task'.$j);
            $task->setUser($admin);
            $manager->persist($task);
        }
        $manager->flush();
    }
}

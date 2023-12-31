<?php

namespace App\Controller;

use App\Entity\Task;
use App\Form\TaskFormType;
use App\Repository\TaskRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;
use Symfony\Component\Routing\Annotation\Route;

class TaskController extends AbstractController
{
    #[Route('/tasks', name: 'task_list')]
    public function listAction(TaskRepository $taskRepository)
    {
        return $this->render('task/list.html.twig', ['tasks' => $taskRepository->findAll()]);
    }

    #[Route('/tasks/create', name: 'task_create')]
    #[Route('/tasks/{id}/edit', name: 'task_edit')]
    public function createAction(Task $task = null, Request $request, EntityManagerInterface $entityManager)
    {
        if (!$task) {
            $task = new Task();
        }
        $form = $this->createForm(TaskFormType::class, $task);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if (null == $task->getId()) {
                /* @phpstan-ignore-next-line */
                $task->setUser($this->getUser());
            }
            $entityManager->persist($task);

            if (null !== $task->getId()) {
                $this->addFlash('success', 'La tâche a été bien été modifiée.');
            } else {
                $this->addFlash('success', 'La tâche a été bien été ajoutée.');
            }
            $entityManager->flush();

            return $this->redirectToRoute('task_list');
        }

        return $this->render('task/create.html.twig', [
            'editMode' => null !== $task->getId(),
            'form' => $form->createView(),
            'task' => $task,
        ]);
    }

    #[Route('/tasks/{id}/toggle', name: 'task_toggle')]
    public function toggleTaskAction(Task $task, EntityManagerInterface $entityManager)
    {
        $task->toggle(!$task->isIsDone());
        $entityManager->flush();

        $this->addFlash('success', sprintf('La tâche %s a bien été marquée comme faite.', $task->getTitle()));

        return $this->redirectToRoute('task_list');
    }

    #[Route('/tasks/{id}/delete', name: 'task_delete')]
    public function deleteTaskAction(Task $task, EntityManagerInterface $entityManager)
    {
        if (
            $task->getUser() === $this->getUser()
            || (7 === $task->getUser()->getId() && $this->isGranted('ROLE_ADMIN'))
        ) {
            $entityManager->remove($task);
            $entityManager->flush();

            $this->addFlash('success', 'La tâche a bien été supprimée.');

            return $this->redirectToRoute('task_list');
        }
        throw new UnauthorizedHttpException('Vous n\'avez pas les droits pour supprimer cette tâche.');
    }
}

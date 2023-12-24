<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegistrationFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

class RegistrationController extends AbstractController
{
    #[Route('/register', name: 'app_register')]
    #[Route('/users/{id}/edit', name: 'user_edit')]
    public function register(User $user = null, Request $request, UserPasswordHasherInterface $userPasswordHasher, EntityManagerInterface $entityManager): Response
    {
        if (!$user) {
            $user = new User();
        }
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // encode the plain password
            $user->setPassword(
                $userPasswordHasher->hashPassword(
                    $user,
                    $form->get('password')->getData()
                )
            );

            $entityManager->persist($user);
            $entityManager->flush();

            if (null !== $user->getId()) {
                $this->addFlash('success', "L'utilisateur a bien été modifié.");
            } else {
                $this->addFlash('success', "L'utilisateur a bien été ajouté.");
            }

            return $this->redirectToRoute('user_list');
        }

        return $this->render('registration/register.html.twig', [
            'editMode' => null !== $user->getId(),
            'registrationForm' => $form->createView(),
        ]);
    }
}

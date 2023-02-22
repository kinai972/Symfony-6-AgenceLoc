<?php

namespace App\Controller\Admin;

use App\Entity\User;
use App\Form\Admin\UserType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

#[Route(path: '/admin/membres', name: 'app_admin_user_')]
class UserController extends AbstractController
{
    #[Route('/', name: 'index', methods: [Request::METHOD_GET])]
    public function index(UserRepository $repository): Response
    {
        return $this->render(view: 'admin/user/index.html.twig', parameters: [
            'users' => $repository->findBy([], ['registeredAt' => 'DESC']),
        ]);
    }

    #[Route('/ajouter', name: 'create', methods: [Request::METHOD_GET, Request::METHOD_POST])]
    public function create(Request $request, UserPasswordHasherInterface $hasher, EntityManagerInterface $manager): Response
    {
        $form = $this->createForm(type: UserType::class, data: $user = new User(), options: [
            'validation_groups' => ['Default', 'password']
        ])->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user->setPassword(
                $hasher->hashPassword(
                    $user,
                    $user->getPlainPassword()
                )
            );

            $manager->persist($user);
            $manager->flush();

            $this->addFlash(type: 'success', message: "Le membre {$user->getUsername()} a été ajouté avec succès.");

            return $this->redirectToRoute(route: 'app_admin_user_index');
        }

        return $this->render(view: 'admin/user/create.html.twig', parameters: [
            'user' => $user,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{id}', name: 'read', methods: [Request::METHOD_GET])]
    public function read(User $user): Response
    {
        return $this->render('admin/user/read.html.twig', [
            'user' => $user,
        ]);
    }

    #[Route('/{id}/modifier', name: 'update', methods: [Request::METHOD_GET, Request::METHOD_POST])]
    public function update(Request $request, User $user, UserPasswordHasherInterface $hasher, EntityManagerInterface $manager): Response
    {
        $form = $this->createForm(type: UserType::class, data: $user)
            ->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if ($user->getPlainPassword() !== null) {
                $user->setPassword(
                    $hasher->hashPassword(
                        $user,
                        $user->getPlainPassword()
                    )
                );
            }

            $manager->persist($user);
            $manager->flush();

            $this->addFlash(type: 'success', message: "Le membre {$user->getUsername()} a été modifié avec succès.");

            return $this->redirectToRoute(route: 'app_admin_user_index');
        }

        return $this->render(view: 'admin/user/update.html.twig', parameters: [
            'user' => $user,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{id}/supprimer', name: 'delete', methods: [Request::METHOD_POST])]
    public function delete(Request $request, User $user, EntityManagerInterface $manager): Response
    {
        if ($this->isCsrfTokenValid('delete' . $user->getId(), $request->request->get('_token'))) {
            $manager->remove($user);
            $manager->flush();

            $this->addFlash(type: 'success', message: "Le membre {$user->getUsername()} a été supprimé avec succès.");
        }

        return $this->redirectToRoute(route: 'app_admin_user_index');
    }
}

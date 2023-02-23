<?php

namespace App\Controller\Front;

use App\Entity\User;
use App\Form\Front\UserType;
use App\Repository\RentingRepository;
use App\Security\UserAuthenticator;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Security\Http\Authentication\UserAuthenticatorInterface;

#[Route(requirements: ['id' => '\d+'])]
class UserController extends AbstractController
{
    #[Route(path: '/connexion', name: 'app_front_user_login')]
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        if ($this->getUser()) {
            return $this->redirectToRoute('app_front_user_account');
        }

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render(view: 'front/user/login.html.twig', parameters: [
            'last_username' => $lastUsername,
            'error' => $error,
        ]);
    }

    #[Route(path: '/deconnexion', name: 'app_front_user_logout')]
    public function logout(): void
    {
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }

    #[Route('/inscription', name: 'app_front_user_signup', methods: [Request::METHOD_GET, Request::METHOD_POST])]
    public function signup(
        Request $request,
        UserPasswordHasherInterface $hasher,
        EntityManagerInterface $manager,
        UserAuthenticatorInterface $userAuthenticator,
        UserAuthenticator $authenticator
    ): Response {
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

            $this->addFlash(type: 'success', message: "Bienvenue, {$user->getUsername()} ! Votre compte a été créé avec succès.");

            return $userAuthenticator->authenticateUser($user, $authenticator, $request);
        }

        return $this->render(view: 'front/user/signup.html.twig', parameters: [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/mon-compte', name: 'app_front_user_account', methods: [Request::METHOD_GET])]
    public function account(RentingRepository $repository): Response
    {
        $user = $this->getUser();

        return $this->render(view: 'front/user/account.html.twig', parameters: [
            'user' => $user,
            'rentings' => $repository->findBy(
                criteria: ['user' => $user],
                orderBy: ['registeredAt' => 'DESC']
            ),
        ]);
    }

    #[Route('/mon-compte/modifier-infos', name: 'app_front_user_update', methods: [Request::METHOD_GET, Request::METHOD_POST])]
    public function update(Request $request, UserPasswordHasherInterface $hasher, EntityManagerInterface $manager): Response
    {
        /** @var User */
        $user = $this->getUser();

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

            $manager->flush();

            $this->addFlash(type: 'success', message: "{$user->getUsername()}, vos informations ont été modifiées avec succès.");

            return $this->redirectToRoute(route: 'app_front_user_account');
        }

        return $this->render(view: 'front/user/update.html.twig', parameters: [
            'form' => $form->createView(),
        ]);
    }
}

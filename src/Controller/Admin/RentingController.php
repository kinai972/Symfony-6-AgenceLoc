<?php

namespace App\Controller\Admin;

use App\Entity\Renting;
use App\Form\Admin\RentingType;
use App\Repository\RentingRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route(path: '/admin/commandes', name: 'app_admin_renting_')]
class RentingController extends AbstractController
{
    #[Route('/', name: 'index', methods: [Request::METHOD_GET])]
    public function index(RentingRepository $repository): Response
    {
        return $this->render(view: 'admin/renting/index.html.twig', parameters: [
            'rentings' => $repository->findBy([], ['registeredAt' => 'DESC']),
        ]);
    }

    #[Route('/creer', name: 'create', methods: [Request::METHOD_GET, Request::METHOD_POST])]
    public function create(Request $request, EntityManagerInterface $manager): Response
    {
        $form = $this->createForm(type: RentingType::class, data: $renting = new Renting(), options: [
            'validation_groups' => ['Default', 'admin']
        ])->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $renting->setVehicleReference(
                $renting->getVehicle()->getId() . ' - ' . $renting->getVehicle()->getTitle()
            );

            $manager->persist($renting);
            $manager->flush();

            $this->addFlash(type: 'success', message: "La commande n° {$renting->getId()} a été créée avec succès.");

            return $this->redirectToRoute(route: 'app_admin_renting_index');
        }

        return $this->render(view: 'admin/renting/create.html.twig', parameters: [
            'renting' => $renting,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{id}/modifier', name: 'update', methods: [Request::METHOD_GET, Request::METHOD_POST])]
    public function update(Renting $renting, Request $request, EntityManagerInterface $manager): Response
    {
        $form = $this->createForm(type: RentingType::class, data: $renting, options: [
            'validation_groups' => ['Default', 'admin']
        ])
            ->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $renting->setVehicleReference(
                $renting->getVehicle()->getId() . ' - ' . $renting->getVehicle()->getTitle()
            );

            $manager->flush();

            $this->addFlash(type: 'success', message: "La commande n° {$renting->getId()} a été modifiée avec succès.");

            return $this->redirectToRoute(route: 'app_admin_renting_index');
        }

        return $this->render(view: 'admin/renting/update.html.twig', parameters: [
            'renting' => $renting,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{id}/supprimer', name: 'delete', methods: [Request::METHOD_POST])]
    public function delete(Request $request, Renting $renting, EntityManagerInterface $manager): Response
    {
        if ($this->isCsrfTokenValid('delete' . $renting->getId(), $request->request->get('_token'))) {
            $manager->remove($renting);

            $this->addFlash(type: 'success', message: "La commande n° {$renting->getId()} a été supprimée avec succès.");

            $manager->flush();
        }

        return $this->redirectToRoute(route: 'app_admin_renting_index');
    }
}

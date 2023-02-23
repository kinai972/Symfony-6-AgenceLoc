<?php

namespace App\Controller\Admin;

use App\Entity\Vehicle;
use App\Form\Admin\VehicleType;
use App\Repository\VehicleRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route(path: '/admin/vehicules', name: 'app_admin_vehicle_', requirements: ['id' => '\d+'])]
class VehicleController extends AbstractController
{
    #[Route('/', name: 'index', methods: [Request::METHOD_GET])]
    public function index(VehicleRepository $repository): Response
    {
        return $this->render(view: 'admin/vehicle/index.html.twig', parameters: [
            'vehicles' => $repository->findBy([], ['registeredAt' => 'DESC']),
        ]);
    }

    #[Route('/ajouter', name: 'create', methods: [Request::METHOD_GET, Request::METHOD_POST])]
    public function create(Request $request, EntityManagerInterface $manager): Response
    {
        $form = $this->createForm(type: VehicleType::class, data: $vehicle = new Vehicle(), options: [
            'validation_groups' => ['Default', 'create']
        ])->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $manager->persist($vehicle);
            $manager->flush();

            $this->addFlash(type: 'success', message: "Le véhicule {$vehicle->getTitle()} a été ajouté avec succès.");

            return $this->redirectToRoute(route: 'app_admin_vehicle_index');
        }

        return $this->render(view: 'admin/vehicle/create.html.twig', parameters: [
            'vehicle' => $vehicle,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{id}/modifier', name: 'update', methods: [Request::METHOD_GET, Request::METHOD_POST])]
    public function update(Vehicle $vehicle, Request $request, EntityManagerInterface $manager): Response
    {
        $form = $this->createForm(type: VehicleType::class, data: $vehicle)
            ->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $vehicle->setUpdatedAt(new \DateTime());

            $manager->flush();

            $this->addFlash(type: 'success', message: "Le véhicule {$vehicle->getTitle()} a été modifié avec succès.");

            return $this->redirectToRoute(route: 'app_admin_vehicle_index');
        }

        return $this->render(view: 'admin/vehicle/update.html.twig', parameters: [
            'vehicle' => $vehicle,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{id}/supprimer', name: 'delete', methods: [Request::METHOD_POST])]
    public function delete(Request $request, Vehicle $vehicle, EntityManagerInterface $manager): Response
    {
        if ($this->isCsrfTokenValid('delete' . $vehicle->getId(), $request->request->get('_token'))) {
            $manager->remove($vehicle);
            $manager->flush();

            $this->addFlash(type: 'success', message: "Le véhicule {$vehicle->getTitle()} a été supprimé avec succès.");
        }

        return $this->redirectToRoute(route: 'app_admin_vehicle_index');
    }
}

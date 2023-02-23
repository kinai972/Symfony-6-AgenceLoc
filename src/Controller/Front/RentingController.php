<?php

namespace App\Controller\Front;

use App\Entity\Renting;
use App\Entity\Vehicle;
use App\Form\Front\RentingType;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[IsGranted('ROLE_USER')]
#[Route(requirements: ['id' => '\d+'])]
class RentingController extends AbstractController
{
    #[Route(
        path: '/reserver-vehicule-{id}',
        name: 'app_front_renting_create',
        methods: [Request::METHOD_GET, Request::METHOD_POST]
    )]
    public function create(Vehicle $vehicle, Request $request, EntityManagerInterface $manager): Response
    {
        $form = $this->createForm(type: RentingType::class, data: $renting = new Renting(), options: [
            'validation_groups' => ['Default', 'user']
        ])->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $rentingDuration =
                $renting
                ->getStartsAt()
                ->diff($renting->getEndsAt())
                ->days;

            $renting->setTotalPrice($vehicle->getDailyPrice() * $rentingDuration);
            $renting->setVehicleReference(
                $vehicle->getId() . ' - ' . $vehicle->getTitle()
            );
            $renting->setVehicle($vehicle);
            $renting->setUser($this->getUser());

            $manager->persist($renting);
            $manager->flush();

            $this->addFlash(type: 'success', message: "Votre commande n° {$renting->getId()} a été créée avec succès.");

            return $this->redirectToRoute(route: 'app_front_user_account');
        }

        return $this->render(view: 'front/renting/create.html.twig', parameters: [
            'vehicle' => $vehicle,
            'form' => $form->createView(),
        ]);
    }
}

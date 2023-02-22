<?php

namespace App\Controller\Front;

use App\Repository\VehicleRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class HomeController extends AbstractController
{
    #[Route(path: '/', name: 'app_front_home_index', methods: [Request::METHOD_GET])]
    public function index(VehicleRepository $repository): Response
    {
        return $this->render(view: 'front/home/index.html.twig', parameters: [
            'vehicles' => $repository->findBy([], ['registeredAt' => 'DESC']),
        ]);
    }
}

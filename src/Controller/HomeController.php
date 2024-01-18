<?php

namespace App\Controller;

use App\Repository\VinylRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(VinylRepository $vinylRepository): Response
    {
        $vinyls = $vinylRepository->findAll();

        return $this->render('home/index.html.twig', [
            'vinyls' => $vinyls,
        ]);
    }
}

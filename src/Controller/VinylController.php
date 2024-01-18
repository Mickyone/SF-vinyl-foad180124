<?php

namespace App\Controller;

use App\Entity\Vinyl;
use App\Form\VinylType;
use App\Repository\VinylRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/vinyl')]
class VinylController extends AbstractController
{
    #[Route('/', name: 'app_vinyl_index', methods: ['GET'])]
    public function index(VinylRepository $vinylRepository): Response
    {
        return $this->render('vinyl/index.html.twig', [
            'vinyls' => $vinylRepository->findAll(),
        ]);
    }    

    #[Route('/new', name: 'app_vinyl_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $vinyl = new Vinyl();
        $form = $this->createForm(VinylType::class, $vinyl);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($vinyl);
            $entityManager->flush();

            return $this->redirectToRoute('app_vinyl_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('vinyl/new.html.twig', [
            'vinyl' => $vinyl,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_vinyl_show', methods: ['GET'])]
    public function show(Vinyl $vinyl): Response
    {
        return $this->render('vinyl/show.html.twig', [
            'vinyl' => $vinyl,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_vinyl_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Vinyl $vinyl, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(VinylType::class, $vinyl);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_vinyl_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('vinyl/edit.html.twig', [
            'vinyl' => $vinyl,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_vinyl_delete', methods: ['POST'])]
    public function delete(Request $request, Vinyl $vinyl, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$vinyl->getId(), $request->request->get('_token'))) {
            $entityManager->remove($vinyl);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_vinyl_index', [], Response::HTTP_SEE_OTHER);
    }
}

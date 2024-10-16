<?php

namespace App\Controller;

use App\Entity\Establishment;
use App\Form\EstablishmentType;
use App\Repository\EstablishmentRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/establishment')]
final class EstablishmentController extends AbstractController
{
    #[Route(name: 'app_establishment_index', methods: ['GET'])]
    public function index(EstablishmentRepository $establishmentRepository): Response
    {
        return $this->render('establishment/index.html.twig', [
            'establishments' => $establishmentRepository->findAll(),
            'nav' => 'establishment',
        ]);
    }

    #[Route('/new', name: 'app_establishment_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $establishment = new Establishment();
        $form = $this->createForm(EstablishmentType::class, $establishment);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($establishment);
            $entityManager->flush();

            return $this->redirectToRoute('app_establishment_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('establishment/new.html.twig', [
            'establishment' => $establishment,
            'form' => $form,
            'nav' => 'establishment',
        ]);
    }

    #[Route('/{id}', name: 'app_establishment_show', methods: ['GET'])]
    public function show(Establishment $establishment): Response
    {
        return $this->render('establishment/show.html.twig', [
            'establishment' => $establishment,
            'nav' => 'establishment',
        ]);
    }

    #[Route('/{id}/edit', name: 'app_establishment_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Establishment $establishment, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(EstablishmentType::class, $establishment);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_establishment_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('establishment/edit.html.twig', [
            'establishment' => $establishment,
            'form' => $form,
            'nav' => 'establishment',
        ]);
    }

    #[Route('/{id}', name: 'app_establishment_delete', methods: ['POST'])]
    public function delete(Request $request, Establishment $establishment, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete' . $establishment->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($establishment);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_establishment_index', [], Response::HTTP_SEE_OTHER);
    }
}
